<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">My Appointments</flux:heading>
        <flux:button href="{{ route('doctors.index') }}" wire:navigate variant="primary" icon="plus">Book New</flux:button>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 rounded-lg border border-zinc-200 bg-zinc-50 p-1 dark:border-zinc-700 dark:bg-zinc-900">
        @foreach(['upcoming' => 'Upcoming', 'past' => 'Past', 'cancelled' => 'Cancelled'] as $value => $label)
            <button wire:click="$set('tab', '{{ $value }}')" wire:navigate
                @class(['flex-1 rounded-md py-2 text-sm font-medium transition', 'bg-white shadow text-zinc-900 dark:bg-zinc-800 dark:text-white' => $tab === $value, 'text-zinc-500 hover:text-zinc-700' => $tab !== $value])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="space-y-3">
        @forelse($this->appointments as $appointment)
            <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <flux:avatar :name="$appointment->doctor->user->name" class="shrink-0" />
                    <div class="flex-1">
                        <p class="font-semibold dark:text-white">Dr. {{ $appointment->doctor->user->name }}</p>
                        <p class="text-sm text-zinc-500">{{ $appointment->doctor->specialization->name }}</p>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $appointment->scheduled_at->format('D, M d Y \a\t h:i A') }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <flux:badge :color="match($appointment->status) { 'confirmed' => 'green', 'pending' => 'yellow', 'completed' => 'blue', default => 'zinc' }">
                            {{ ucfirst($appointment->status) }}
                        </flux:badge>
                        <flux:badge :color="$appointment->payment_status === 'paid' ? 'green' : 'red'" size="sm">
                            {{ ucfirst($appointment->payment_status) }}
                        </flux:badge>
                        <flux:button href="{{ route('patient.appointment', $appointment->id) }}" wire:navigate size="sm" variant="ghost">Details</flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-zinc-200 py-16 text-center dark:border-zinc-700">
                <flux:icon.calendar class="mx-auto mb-3 size-12 text-zinc-300" />
                <p class="text-zinc-500">No {{ $tab }} appointments</p>
            </div>
        @endforelse
    </div>

    {{ $this->appointments->links() }}
</div>
