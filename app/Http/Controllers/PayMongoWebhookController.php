<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PayMongoWebhookController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function __invoke(Request $request): Response
    {
        try {
            $this->paymentService->handleWebhook($request);

            return response('OK', 200);
        } catch (\Throwable $e) {
            Log::error('PayMongo webhook error', [
                'message' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response($e->getMessage(), 400);
        }
    }
}
