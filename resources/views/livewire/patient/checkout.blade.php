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
                    @foreach(['qrph' => 'QRPh (Scan to Pay)', 'gcash' => 'GCash', 'maya' => 'Maya', 'grab_pay' => 'GrabPay', 'card' => 'Credit / Debit Card'] as $value => $label)
                        <div wire:click="$set('paymentMethod', '{{ $value }}')"
                            @class(['flex cursor-pointer items-center gap-3 rounded-lg border-2 p-4 transition', 'border-blue-500 bg-blue-50 dark:bg-blue-950' => $paymentMethod === $value, 'border-zinc-200 hover:border-zinc-300 dark:border-zinc-700' => $paymentMethod !== $value])>
                            <div @class(['h-4 w-4 rounded-full border-2 shrink-0', 'border-blue-500 bg-blue-500' => $paymentMethod === $value, 'border-zinc-300' => $paymentMethod !== $value])></div>
                            <div>
                                <span class="font-medium dark:text-white">{{ $label }}</span>
                                @if($value === 'qrph')
                                    <p class="text-xs text-zinc-500">Pay via any bank or e-wallet app that supports QR Ph</p>
                                @endif
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
