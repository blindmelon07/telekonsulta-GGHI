<div class="mx-auto max-w-2xl space-y-6">
    <flux:heading size="xl">Complete Payment</flux:heading>

    <div class="grid gap-6 md:grid-cols-5">
        {{-- Payment Form --}}
        <div class="md:col-span-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg" class="mb-4">Payment Method</flux:heading>

                <div class="space-y-3">
                    @foreach(['gcash' => ['label' => 'GCash', 'color' => 'blue'], 'maya' => ['label' => 'Maya', 'color' => 'green'], 'grab_pay' => ['label' => 'GrabPay', 'color' => 'green'], 'card' => ['label' => 'Credit / Debit Card', 'color' => 'zinc']] as $value => $method)
                        <label @class(['flex cursor-pointer items-center gap-3 rounded-lg border-2 p-4 transition', 'border-blue-500 bg-blue-50 dark:bg-blue-950' => $paymentMethod === $value, 'border-zinc-200 dark:border-zinc-700' => $paymentMethod !== $value])>
                            <input type="radio" wire:model="paymentMethod" value="{{ $value }}" class="sr-only">
                            <div @class(['h-4 w-4 rounded-full border-2', 'border-blue-500 bg-blue-500' => $paymentMethod === $value, 'border-zinc-300' => $paymentMethod !== $value])></div>
                            <span class="font-medium dark:text-white">{{ $method['label'] }}</span>
                        </label>
                    @endforeach
                </div>

                <flux:button
                    wire:click="submitPayment"
                    wire:loading.attr="disabled"
                    wire:confirm="{{ in_array($paymentMethod, ['gcash','maya','grab_pay']) ? 'You will be redirected to complete payment. Continue?' : '' }}"
                    variant="primary"
                    class="mt-6 w-full"
                    size="lg"
                >
                    <span wire:loading.remove>Pay ₱{{ number_format($this->appointment->amount / 100, 2) }}</span>
                    <span wire:loading>Processing...</span>
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
                        <dd class="font-medium dark:text-white">Dr. {{ $this->appointment->doctor->user->name }}</dd>
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
</div>
