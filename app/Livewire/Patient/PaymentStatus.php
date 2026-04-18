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
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Status')]
#[Layout('layouts.patient')]
class PaymentStatus extends Component
{
    #[Locked]
    public int $appointmentId;

    public function mount(?int $appointmentId = null): void
    {
        // Route param for /payment/status/{appointmentId}
        // Query param for /payment/callback?appointment=X (PayMongo redirect)
        $id = $appointmentId ?? (int) request()->query('appointment');

        $appointment = Appointment::with('payment')
            ->where('id', $id)
            ->where('patient_id', auth()->id())
            ->firstOrFail();

        $this->appointmentId = $appointment->id;

        // Fallback: when arriving at status page, sync payment if webhook hasn't fired yet.
        $payment = $appointment->payment;
        if ($payment && ! $payment->isPaid()) {
            $paymentService = app(PaymentService::class);
            if ($payment->paymongo_source_id) {
                $this->processSourceOnCallback($payment, $paymentService);
            } elseif ($payment->paymongo_payment_intent_id) {
                $this->syncIntentPaymentStatus($payment, $paymentService);
            }
        }
    }

    #[Computed]
    public function appointment(): Appointment
    {
        return Appointment::with('payment', 'doctor.user')->findOrFail($this->appointmentId);
    }

    public function checkPaymentStatus(PaymentService $paymentService): void
    {
        // Clear cached computed value to get fresh data
        unset($this->appointment);
        $appointment = $this->appointment;

        if (\in_array($appointment->payment_status, ['paid', 'refunded'])) {
            $this->dispatch('payment-resolved');

            return;
        }

        // Fallback: if webhook hasn't fired, check payment status directly from PayMongo
        $payment = $appointment->payment;
        if ($payment && ! $payment->isPaid()) {
            if ($payment->paymongo_source_id) {
                $this->syncSourcePaymentStatus($payment, $paymentService);
            } elseif ($payment->paymongo_payment_intent_id) {
                $this->syncIntentPaymentStatus($payment, $paymentService);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.patient.payment-status');
    }

    private function processSourceOnCallback(?Payment $payment, PaymentService $paymentService): void
    {
        if (! $payment || ! $payment->paymongo_source_id || $payment->isPaid()) {
            return;
        }

        $source = $paymentService->retrieveSource($payment->paymongo_source_id);
        $sourceStatus = $source['attributes']['status'] ?? null;

        if ($sourceStatus === 'chargeable') {
            $paymentService->chargeSource($payment->paymongo_source_id, $payment->amount);
        }
    }

    private function syncIntentPaymentStatus(Payment $payment, PaymentService $paymentService): void
    {
        $intent = $paymentService->retrievePaymentIntent($payment->paymongo_payment_intent_id);
        $status = $intent['attributes']['status'] ?? null;

        if ($status === 'succeeded') {
            $payment->update(['status' => 'paid', 'paid_at' => now()]);
            $payment->appointment->update(['payment_status' => 'paid']);
            app(AppointmentService::class)->markPaid($payment->appointment);

            if ($payment->appointment->type === 'teleconsultation') {
                CreateZoomMeetingJob::dispatch($payment->appointment->id);
            }

            SendPaymentReceiptJob::dispatch($payment->id);
            PaymentConfirmed::dispatch($payment->appointment);
            unset($this->appointment);
        } elseif (\in_array($status, ['awaiting_payment_method', 'cancelled'])) {
            $payment->update(['status' => 'failed']);
        }
    }

    private function syncSourcePaymentStatus(Payment $payment, PaymentService $paymentService): void
    {
        $source = $paymentService->retrieveSource($payment->paymongo_source_id);
        $sourceStatus = $source['attributes']['status'] ?? null;

        if ($sourceStatus === 'chargeable') {
            $paymentService->chargeSource($payment->paymongo_source_id, $payment->amount);
        } elseif ($sourceStatus === 'paid') {
            $payment->update(['status' => 'paid', 'paid_at' => now()]);
            $payment->appointment->update(['payment_status' => 'paid']);
            app(AppointmentService::class)->markPaid($payment->appointment);
            unset($this->appointment);
        } elseif ($sourceStatus === 'cancelled' || $sourceStatus === 'expired') {
            $payment->update(['status' => 'failed']);
        }
    }
}
