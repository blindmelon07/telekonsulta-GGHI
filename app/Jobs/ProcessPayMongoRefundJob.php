<?php

namespace App\Jobs;

use App\Models\AppNotification;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessPayMongoRefundJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly int $paymentId) {}

    public function handle(): void
    {
        $payment = Payment::findOrFail($this->paymentId);

        if (! $payment->isPaid() || ! $payment->paymongo_payment_intent_id) {
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(config('services.paymongo.secret_key').':'),
            'Content-Type' => 'application/json',
        ])->post("https://api.paymongo.com/v1/refunds", [
            'data' => [
                'attributes' => [
                    'amount' => $payment->amount,
                    'payment_id' => $payment->paymongo_payment_intent_id,
                    'reason' => 'others',
                    'notes' => 'Appointment cancellation refund',
                ],
            ],
        ]);

        if ($response->successful()) {
            $payment->update(['status' => 'refunded']);
            $payment->appointment->update(['payment_status' => 'refunded']);

            AppNotification::create([
                'user_id' => $payment->patient_id,
                'type' => 'payment_refunded',
                'title' => 'Refund Processed',
                'message' => '₱'.number_format($payment->amount / 100, 2).' refund has been initiated.',
                'data' => ['payment_id' => $payment->id],
            ]);
        } else {
            Log::error('PayMongo refund failed', [
                'payment_id' => $payment->id,
                'response' => $response->json(),
            ]);
        }
    }
}
