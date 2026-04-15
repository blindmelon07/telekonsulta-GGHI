<div class="space-y-6">
    <flux:heading size="xl">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, Dr. {{ auth()->user()->name }}!</flux:heading>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-blue-100 bg-blue-50 p-6 dark:border-blue-900 dark:bg-blue-950">
            <p class="text-sm text-blue-600">Today's Appointments</p>
            <p class="mt-1 text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $this->stats['today'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Upcoming</p>
            <p class="mt-1 text-3xl font-bold dark:text-white">{{ $this->stats['upcoming'] }}</p>
        </div>
        <div class="rounded-xl border border-green-100 bg-green-50 p-6 dark:border-green-900 dark:bg-green-950">
            <p class="text-sm text-green-600">Completed This Month</p>
            <p class="mt-1 text-3xl font-bold text-green-700 dark:text-green-300">{{ $this->stats['completed_month'] }}</p>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-6 dark:border-emerald-900 dark:bg-emerald-950">
            <p class="text-sm text-emerald-600">Earnings This Month</p>
            <p class="mt-1 text-3xl font-bold text-emerald-700 dark:text-emerald-300">₱{{ number_format($this->stats['earnings_month'], 0) }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 p-4 dark:border-zinc-700">
            <flux:heading size="lg">Today's Schedule — {{ today()->format('D, M d') }}</flux:heading>
        </div>
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($this->todaySchedule as $appointment)
                <div class="flex items-center gap-4 p-4">
                    <div class="w-16 text-center">
                        <p class="text-sm font-bold text-blue-600">{{ $appointment->scheduled_at->format('h:i') }}</p>
                        <p class="text-xs text-zinc-400">{{ $appointment->scheduled_at->format('A') }}</p>
                    </div>
                    <flux:avatar :name="$appointment->patient->name" />
                    <div class="flex-1">
                        <p class="font-medium dark:text-white">{{ $appointment->patient->name }}</p>
                        <p class="text-sm text-zinc-500">{{ $appointment->reason }}</p>
                    </div>
                    <flux:badge :color="$appointment->type === 'teleconsultation' ? 'green' : 'blue'" size="sm">
                        {{ $appointment->type === 'teleconsultation' ? 'Online' : 'In-person' }}
                    </flux:badge>
                    <flux:badge :color="match($appointment->status) { 'confirmed' => 'green', 'pending' => 'yellow', default => 'zinc' }" size="sm">
                        {{ ucfirst($appointment->status) }}
                    </flux:badge>
                    <flux:button href="{{ route('doctor.appointment', $appointment->id) }}" wire:navigate size="sm" variant="ghost">View</flux:button>
                </div>
            @empty
                <div class="p-8 text-center text-zinc-500">
                    <flux:icon.calendar class="mx-auto mb-3 size-12 opacity-40" />
                    <p>No appointments today</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
