<div class="mx-auto max-w-lg py-16 text-center">
    @if($this->appointment->payment_status === 'paid')
        <flux:icon.check-circle class="mx-auto mb-4 size-20 text-green-500" />
        <flux:heading size="xl" class="text-green-600">Payment Successful!</flux:heading>
        <p class="mt-2 text-zinc-500">Your appointment has been confirmed.</p>

        <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-6 text-left dark:border-green-900 dark:bg-green-950">
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Doctor</dt>
                    <dd class="font-medium">{{ $this->appointment->doctor->user->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Date & Time</dt>
                    <dd class="font-medium">{{ $this->appointment->scheduled_at->format('M d, Y h:i A') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-zinc-500">Amount Paid</dt>
                    <dd class="font-bold text-green-600">₱{{ number_format($this->appointment->amount / 100, 2) }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-6 flex justify-center gap-3">
            <flux:button href="{{ route('patient.appointments.show', $this->appointment->id) }}" wire:navigate variant="primary">View Appointment</flux:button>
            <flux:button href="{{ route('patient.dashboard') }}" wire:navigate variant="ghost">Dashboard</flux:button>
        </div>
    @elseif($this->appointment->payment_status === 'unpaid')
        <div wire:poll.3s="checkPaymentStatus">
            <flux:icon.clock class="mx-auto mb-4 size-20 text-yellow-500 animate-pulse" />
            <flux:heading size="xl">Waiting for Payment...</flux:heading>
            <p class="mt-2 text-zinc-500">Please complete your payment in the opened window. This page will update automatically.</p>
            <div class="mt-6">
                <flux:button href="{{ route('patient.checkout', $this->appointment->id) }}" wire:navigate variant="ghost">Try Again</flux:button>
            </div>
        </div>
    @else
        <flux:icon.x-circle class="mx-auto mb-4 size-20 text-red-500" />
        <flux:heading size="xl" class="text-red-600">Payment Failed</flux:heading>
        <p class="mt-2 text-zinc-500">Something went wrong with your payment. Please try again.</p>
        <div class="mt-6">
            <flux:button href="{{ route('patient.checkout', $this->appointment->id) }}" wire:navigate variant="primary">Try Again</flux:button>
        </div>
    @endif
</div>
