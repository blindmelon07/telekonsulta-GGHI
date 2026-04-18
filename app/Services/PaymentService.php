<?php

namespace App\Services;

use App\Events\PaymentConfirmed;
use App\Jobs\CreateZoomMeetingJob;
use App\Jobs\SendPaymentReceiptJob;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private string $baseUrl = 'https://api.paymongo.com/v1';

    private function headers(): array
    {
        return [
            'Authorization' => 'Basic '.base64_encode(config('services.paymongo.secret_key').':'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @param  array<string>  $paymentMethodAllowed
     */
    public function createPaymentIntent(int $amountCentavos, string $description, array $metadata = [], array $paymentMethodAllowed = ['card', 'gcash', 'maya', 'grab_pay']): array
    {
        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/payment_intents", [
                'data' => [
                    'attributes' => [
                        'amount' => $amountCentavos,
                        'payment_method_allowed' => $paymentMethodAllowed,
                        'payment_method_options' => [
                            'card' => ['request_three_d_secure' => 'any'],
                        ],
                        'currency' => 'PHP',
                        'description' => $description,
                        'metadata' => $metadata,
                        'capture_type' => 'automatic',
                    ],
                ],
            ]);

        $data = $response->json('data');

        if ($data === null) {
            $error = $response->json('errors.0.detail') ?? $response->json('errors.0.code') ?? 'Unknown PayMongo error';
            Log::error('PayMongo createPaymentIntent failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException("Payment gateway error: {$error}");
        }

        return $data;
    }

    /** @param array<string, mixed> $redirectUrls */
    public function createSource(string $type, int $amount, array $redirectUrls): array
    {
        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/sources", [
                'data' => [
                    'attributes' => [
                        'amount' => $amount,
                        'redirect' => $redirectUrls,
                        'type' => $type,
                        'currency' => 'PHP',
                    ],
                ],
            ]);

        $data = $response->json('data');

        if ($data === null) {
            $error = $response->json('errors.0.detail') ?? $response->json('errors.0.code') ?? 'Unknown PayMongo error';
            Log::error('PayMongo createSource failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException("Payment gateway error: {$error}");
        }

        return $data;
    }

    public function retrieveSource(string $sourceId): array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/sources/{$sourceId}");

        return $response->json('data') ?? [];
    }

    public function chargeSource(string $sourceId, int $amount): void
    {
        Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/payments", [
                'data' => [
                    'attributes' => [
                        'amount' => $amount,
                        'source' => ['id' => $sourceId, 'type' => 'source'],
                        'currency' => 'PHP',
                    ],
                ],
            ]);
    }

    public function createQrphPaymentMethod(): array
    {
        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/payment_methods", [
                'data' => [
                    'attributes' => [
                        'type' => 'qrph',
                    ],
                ],
            ]);

        $data = $response->json('data');

        if ($data === null) {
            $error = $response->json('errors.0.detail') ?? $response->json('errors.0.code') ?? 'Unknown PayMongo error';
            Log::error('PayMongo createQrphPaymentMethod failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException("Payment gateway error: {$error}");
        }

        return $data;
    }

    public function attachPaymentMethod(string $intentId, string $methodId, string $returnUrl = ''): array
    {
        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/payment_intents/{$intentId}/attach", [
                'data' => [
                    'attributes' => [
                        'payment_method' => $methodId,
                        'return_url' => $returnUrl ?: route('patient.payment.callback'),
                    ],
                ],
            ]);

        $data = $response->json('data');

        if ($data === null) {
            $error = $response->json('errors.0.detail') ?? $response->json('errors.0.code') ?? 'Unknown PayMongo error';
            Log::error('PayMongo attachPaymentMethod failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException("Payment gateway error: {$error}");
        }

        Log::info('PayMongo attachPaymentMethod response', [
            'status' => $data['attributes']['status'] ?? null,
            'next_action' => $data['attributes']['next_action'] ?? null,
        ]);

        return $data;
    }

    public function createPaymentMethod(string $type, array $billingDetails = [], array $details = []): array
    {
        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/payment_methods", [
                'data' => [
                    'attributes' => array_filter([
                        'type' => $type,
                        'billing' => $billingDetails ?: null,
                        'details' => $details ?: null,
                    ]),
                ],
            ]);

        return $response->json('data');
    }

    public function retrievePaymentIntent(string $intentId): array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/payment_intents/{$intentId}");

        return $response->json('data');
    }

    public function handleWebhook(Request $request): void
    {
        $this->verifyWebhookSignature($request);

        $event = $request->input('data.attributes.type');
        $resource = $request->input('data.attributes.data');

        match ($event) {
            'payment.paid' => $this->handlePaymentPaid($resource),
            'source.chargeable' => $this->handleSourceChargeable($resource),
            'payment.failed' => $this->handlePaymentFailed($resource),
            default => Log::info("Unhandled PayMongo webhook: {$event}"),
        };
    }

    private function verifyWebhookSignature(Request $request): void
    {
        $signature = $request->header('Paymongo-Signature');
        $secret = config('services.paymongo.webhook_secret');
        $payload = $request->getContent();

        [$timestamp, $testSignature, $liveSignature] = array_pad(
            array_map(fn ($part) => explode('=', $part, 2)[1] ?? '', explode(',', $signature ?? '')),
            3,
            ''
        );

        $computed = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        if (! hash_equals($computed, $testSignature) && ! hash_equals($computed, $liveSignature)) {
            abort(400, 'Invalid webhook signature');
        }
    }

    private function handlePaymentPaid(array $resource): void
    {
        $intentId = $resource['attributes']['payment_intent_id'] ?? null;
        $sourceId = $resource['attributes']['source']['id'] ?? null;

        $payment = null;

        if ($intentId) {
            $payment = Payment::where('paymongo_payment_intent_id', $intentId)->first();
        } elseif ($sourceId) {
            $payment = Payment::where('paymongo_source_id', $sourceId)->first();
        }

        if (! $payment || $payment->isPaid()) {
            return;
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $payment->appointment->update(['payment_status' => 'paid']);

        app(AppointmentService::class)->markPaid($payment->appointment);

        if ($payment->appointment->type === 'teleconsultation') {
            CreateZoomMeetingJob::dispatch($payment->appointment->id);
        }

        SendPaymentReceiptJob::dispatch($payment->id);

        PaymentConfirmed::dispatch($payment->appointment);
    }

    private function handleSourceChargeable(array $resource): void
    {
        $sourceId = $resource['id'];
        $amount = $resource['attributes']['amount'];

        $payment = Payment::where('paymongo_source_id', $sourceId)->first();

        if (! $payment || $payment->isPaid()) {
            return;
        }

        $this->chargeSource($sourceId, $amount);
    }

    private function handlePaymentFailed(array $resource): void
    {
        $intentId = $resource['attributes']['payment_intent_id'] ?? null;

        if (! $intentId) {
            return;
        }

        $payment = Payment::where('paymongo_payment_intent_id', $intentId)->first();
        $payment?->update(['status' => 'failed']);
    }
}
