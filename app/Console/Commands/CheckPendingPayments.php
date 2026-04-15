<?php

namespace App\Console\Commands;

use App\Events\PaymentConfirmed;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('payments:check-pending')]
#[Description('Re-query PayMongo for stuck pending payments')]
class CheckPendingPayments extends Command
{
    public function handle(PaymentService $paymentService): int
    {
        $payments = Payment::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(5))
            ->whereNotNull('paymongo_payment_intent_id')
            ->get();

        $resolved = 0;

        foreach ($payments as $payment) {
            $intent = $paymentService->retrievePaymentIntent($payment->paymongo_payment_intent_id);
            $status = $intent['attributes']['status'] ?? null;

            if ($status === 'succeeded') {
                $payment->update(['status' => 'paid', 'paid_at' => now()]);
                $payment->appointment->update(['payment_status' => 'paid']);
                PaymentConfirmed::dispatch($payment->appointment);
                $resolved++;
            } elseif ($status === 'payment_error') {
                $payment->update(['status' => 'failed']);
            }
        }

        $this->info("Resolved {$resolved} of {$payments->count()} pending payment(s).");

        return self::SUCCESS;
    }
}
