<div class="mx-auto max-w-2xl space-y-6">
    <flux:heading size="xl">Complete Payment</flux:heading>

    @if($qrCodeImage)
        {{-- QRPh QR Code Display --}}
        <div wire:poll.3s="pollQrPayment" class="grid gap-6 md:grid-cols-5">
            <div class="md:col-span-3">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 text-center dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:heading size="lg" class="mb-2">Scan to Pay</flux:heading>
                    <p class="mb-4 text-sm text-zinc-500">Open your bank or e-wallet app and scan the QR code below.</p>

                    <div class="mx-auto mb-4 w-fit rounded-xl border border-zinc-200 p-3 dark:border-zinc-700">
                        <img src="{{ $qrCodeImage }}" alt="QRPh Payment Code" class="h-56 w-56" />
                    </div>

                    <div class="flex items-center justify-center gap-2 text-sm text-zinc-500">
                        <flux:icon name="arrow-path" class="h-4 w-4 animate-spin" />
                        Waiting for payment confirmation...
                    </div>

                    <flux:button wire:click="$set('qrCodeImage', null)" variant="ghost" size="sm" class="mt-4">
                        Cancel &amp; Go Back
                    </flux:button>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="md:col-span-2">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:heading size="lg" class="mb-4">Summary</flux:heading>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Doctor</dt>
                            <dd class="font-medium dark:text-white">{{ $this->appointment->doctor->user->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Type</dt>
                            <dd class="font-medium dark:text-white">{{ ucfirst(str_replace('_', '-', $this->appointment->type)) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Date</dt>
                            <dd class="font-medium dark:text-white">{{ $this->appointment->scheduled_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Time</dt>
                            <dd class="font-medium dark:text-white">{{ $this->appointment->scheduled_at->format('h:i A') }}</dd>
                        </div>
                        <div class="border-t border-zinc-200 pt-3 dark:border-zinc-700">
                            <div class="flex justify-between">
                                <dt class="font-semibold dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-blue-600">₱{{ number_format($this->appointment->amount / 100, 2) }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    @else
    <div class="grid gap-6 md:grid-cols-5">
        {{-- Payment Form --}}
        <div class="md:col-span-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Payment Method</flux:heading>

                <div class="space-y-3">
                    {{-- QRPh: active --}}
                    <div wire:click="$set('paymentMethod', 'qrph')"
                        @class(['flex cursor-pointer items-center gap-3 rounded-lg border-2 p-4 transition', 'border-blue-500 bg-blue-50 dark:bg-blue-950' => $paymentMethod === 'qrph', 'border-zinc-200 hover:border-zinc-300 dark:border-zinc-700' => $paymentMethod !== 'qrph'])>
                        <div @class(['h-4 w-4 rounded-full border-2 shrink-0', 'border-blue-500 bg-blue-500' => $paymentMethod === 'qrph', 'border-zinc-300' => $paymentMethod !== 'qrph'])></div>
                        <div>
                            <span class="font-medium dark:text-white">QRPh (Scan to Pay)</span>
                            <p class="text-xs text-zinc-500">Pay via any bank or e-wallet app that supports QR Ph</p>
                        </div>
                    </div>

                    {{-- Coming Soon methods --}}
                    @foreach(['GCash', 'Maya', 'GrabPay', 'Credit / Debit Card'] as $label)
                        <div class="flex cursor-not-allowed items-center gap-3 rounded-lg border-2 border-zinc-200 bg-zinc-50 p-4 opacity-60 dark:border-zinc-700 dark:bg-zinc-800/50">
                            <div class="h-4 w-4 rounded-full border-2 border-zinc-300 shrink-0"></div>
                            <div class="flex flex-1 items-center justify-between">
                                <span class="font-medium text-zinc-400 dark:text-zinc-500">{{ $label }}</span>
                                <span class="rounded-full bg-zinc-200 px-2 py-0.5 text-xs font-semibold text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400">Coming Soon</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <flux:button
                    wire:click="submitPayment"
                    wire:loading.attr="disabled"
                    variant="primary"
                    class="mt-6 w-full"
                >
                    <span wire:loading.remove wire:target="submitPayment">Pay ₱{{ number_format($this->appointment->amount / 100, 2) }}</span>
                    <span wire:loading wire:target="submitPayment">Processing...</span>
                </flux:button>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="md:col-span-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Summary</flux:heading>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Doctor</dt>
                        <dd class="font-medium dark:text-white">{{ $this->appointment->doctor->user->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Type</dt>
                        <dd class="font-medium dark:text-white">{{ ucfirst(str_replace('_', '-', $this->appointment->type)) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Date</dt>
                        <dd class="font-medium dark:text-white">{{ $this->appointment->scheduled_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">Time</dt>
                        <dd class="font-medium dark:text-white">{{ $this->appointment->scheduled_at->format('h:i A') }}</dd>
                    </div>
                    <div class="border-t border-zinc-200 pt-3 dark:border-zinc-700">
                        <div class="flex justify-between">
                            <dt class="font-semibold dark:text-white">Total</dt>
                            <dd class="text-lg font-bold text-blue-600">₱{{ number_format($this->appointment->amount / 100, 2) }}</dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    @endif
</div>
