<?php

namespace App\Jobs;

use App\Models\AppNotification;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPaymentReceiptJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly int $paymentId) {}

    public function handle(): void
    {
        $payment = Payment::with('appointment.doctor.user', 'patient')->findOrFail($this->paymentId);

        AppNotification::create([
            'user_id' => $payment->patient_id,
            'type' => 'payment_receipt',
            'title' => 'Payment Successful',
            'message' => 'Your payment of ₱'.number_format($payment->amount / 100, 2)
                .' has been received. Your appointment is now confirmed.',
            'data' => [
                'payment_id' => $payment->id,
                'appointment_id' => $payment->appointment_id,
                'amount' => $payment->amount,
            ],
        ]);
    }
}
