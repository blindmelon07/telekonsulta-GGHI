<?php

namespace App\Livewire\Patient;

use App\Events\PaymentConfirmed;
use App\Jobs\CreateZoomMeetingJob;
use App\Jobs\SendPaymentReceiptJob;
use App\Models\Appointment;
use App\Models\Payment;
use App\Services\AppointmentService;
use App\Services\PaymentService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Checkout')]
#[Layout('layouts.patient')]
class Checkout extends Component
{
    #[Locked]
    public int $appointmentId;

    #[Validate('required|in:gcash,maya,card,grab_pay,qrph')]
    public string $paymentMethod = 'qrph';

    public bool $isProcessing = false;

    public ?string $qrCodeImage = null;

    public ?string $pendingIntentId = null;

    public function mount(int $appointment): void
    {
        $appointment = Appointment::where('id', $appointment)
            ->where('patient_id', auth()->id())
            ->firstOrFail();

        if ($appointment->isPaid()) {
            $this->redirectRoute('patient.payment-status', $appointment->id);

            return;
        }

        $this->appointmentId = $appointment->id;
    }

    #[Computed]
    public function appointment(): Appointment
    {
        return Appointment::with('doctor.user', 'doctor.specialization')
            ->findOrFail($this->appointmentId);
    }

    public function submitPayment(PaymentService $paymentService): void
    {
        $this->validate();
        $this->isProcessing = true;

        $appointment = $this->appointment;

        // Void any previously abandoned pending payments before creating a new one
        Payment::where('appointment_id', $appointment->id)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);

        if (\in_array($this->paymentMethod, ['gcash', 'maya', 'grab_pay'])) {
            // Sources API flow (e-wallets)
            $source = $paymentService->createSource(
                type: $this->paymentMethod,
                amount: $appointment->amount,
                redirectUrls: [
                    'success' => route('patient.payment.callback', ['status' => 'success', 'appointment' => $appointment->id]),
                    'failed' => route('patient.payment.callback', ['status' => 'failed', 'appointment' => $appointment->id]),
                ]
            );

            Payment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => auth()->id(),
                'paymongo_source_id' => $source['id'],
                'method' => $this->paymentMethod,
                'amount' => $appointment->amount,
                'currency' => 'PHP',
                'status' => 'pending',
            ]);

            $this->redirect($source['attributes']['redirect']['checkout_url']);
        } elseif ($this->paymentMethod === 'qrph') {
            // QRPh uses Payment Intents + Payment Methods flow
            $returnUrl = route('patient.payment.callback', ['status' => 'success', 'appointment' => $appointment->id]);

            $intent = $paymentService->createPaymentIntent(
                amountCentavos: $appointment->amount,
                description: "MediConnect Appointment #{$appointment->id}",
                metadata: ['appointment_id' => (string) $appointment->id, 'patient_id' => (string) auth()->id()],
                paymentMethodAllowed: ['qrph']
            );

            $method = $paymentService->createQrphPaymentMethod();

            $attached = $paymentService->attachPaymentMethod($intent['id'], $method['id'], $returnUrl);

            Payment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => auth()->id(),
                'paymongo_payment_intent_id' => $intent['id'],
                'method' => 'qrph',
                'amount' => $appointment->amount,
                'currency' => 'PHP',
                'status' => 'pending',
            ]);

            $nextAction = $attached['attributes']['next_action'] ?? [];

            if (($nextAction['type'] ?? null) === 'consume_qr') {
                $this->qrCodeImage = $nextAction['code']['image_url'] ?? null;
                $this->pendingIntentId = $intent['id'];
            } elseif ($qrUrl = ($nextAction['redirect']['url'] ?? $nextAction['url'] ?? null)) {
                $this->redirect($qrUrl);
            }
        } else {
            // Card payment via payment intent
            $intent = $paymentService->createPaymentIntent(
                amountCentavos: $appointment->amount,
                description: "MediConnect Appointment #{$appointment->id}",
                metadata: ['appointment_id' => (string) $appointment->id, 'patient_id' => (string) auth()->id()]
            );

            Payment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => auth()->id(),
                'paymongo_payment_intent_id' => $intent['id'],
                'method' => 'card',
                'amount' => $appointment->amount,
                'currency' => 'PHP',
                'status' => 'pending',
            ]);

            $this->dispatch('payment-intent-created',
                clientKey: $intent['attributes']['client_key'],
                appointmentId: $appointment->id
            );
        }
    }

    public function pollQrPayment(PaymentService $paymentService): void
    {
        if (! $this->pendingIntentId) {
            return;
        }

        $intent = $paymentService->retrievePaymentIntent($this->pendingIntentId);
        $status = $intent['attributes']['status'] ?? null;

        if ($status === 'succeeded') {
            // Mark paid locally in case the webhook hasn't fired yet
            $payment = Payment::where('paymongo_payment_intent_id', $this->pendingIntentId)
                ->where('appointment_id', $this->appointmentId)
                ->first();

            if ($payment && ! $payment->isPaid()) {
                $payment->update(['status' => 'paid', 'paid_at' => now()]);
                $payment->appointment->update(['payment_status' => 'paid']);
                app(AppointmentService::class)->markPaid($payment->appointment);

                if ($payment->appointment->type === 'teleconsultation') {
                    CreateZoomMeetingJob::dispatch($payment->appointment->id);
                }

                SendPaymentReceiptJob::dispatch($payment->id);
                PaymentConfirmed::dispatch($payment->appointment);
            }

            $this->redirectRoute('patient.payment-status', $this->appointmentId);
        } elseif ($status === 'awaiting_payment_method') {
            // Payment expired or failed — reset so user can try again
            $this->qrCodeImage = null;
            $this->pendingIntentId = null;
            $this->isProcessing = false;
        }
    }

    #[On('payment-status-updated')]
    public function onPaymentStatusUpdated(): void
    {
        $this->redirectRoute('patient.payment-status', $this->appointmentId);
    }

    public function render(): View
    {
        return view('livewire.patient.checkout');
    }
}
