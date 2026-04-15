<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\PaymentService;
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

    #[Validate('required|in:gcash,maya,card,grab_pay')]
    public string $paymentMethod = 'gcash';

    public bool $isProcessing = false;

    public function mount(int $appointmentId): void
    {
        $appointment = Appointment::where('id', $appointmentId)
            ->where('patient_id', auth()->id())
            ->firstOrFail();

        if ($appointment->isPaid()) {
            $this->redirectRoute('patient.payment-status', $appointmentId);

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

        if (in_array($this->paymentMethod, ['gcash', 'maya', 'grab_pay'])) {
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

            $this->redirectAway($source['attributes']['redirect']['checkout_url']);
        } else {
            // Card payment via payment intent
            $intent = $paymentService->createPaymentIntent(
                amountCentavos: $appointment->amount,
                description: "MediConnect Appointment #{$appointment->id}",
                metadata: ['appointment_id' => $appointment->id, 'patient_id' => auth()->id()]
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

    #[On('payment-status-updated')]
    public function onPaymentStatusUpdated(): void
    {
        $this->redirectRoute('patient.payment-status', $this->appointmentId);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.checkout');
    }
}
