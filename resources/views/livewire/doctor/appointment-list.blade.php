<div class="space-y-6">
    <flux:heading size="xl">Appointments</flux:heading>

    <div class="flex flex-col gap-3 sm:flex-row">
        <flux:input type="date" wire:model.live="date" class="sm:w-48" />
        <flux:select wire:model.live="status" placeholder="All Statuses" class="sm:w-48">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="no_show">No Show</option>
        </flux:select>
    </div>

    <div class="space-y-3">
        @forelse($this->appointments as $appointment)
            <div class="flex items-center gap-4 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="w-20 text-center">
                    <p class="text-sm font-bold dark:text-white">{{ $appointment->scheduled_at->format('h:i') }}</p>
                    <p class="text-xs text-zinc-400">{{ $appointment->scheduled_at->format('A') }}</p>
                </div>
                <flux:avatar :name="$appointment->patient->name" />
                <div class="flex-1">
                    <p class="font-semibold dark:text-white">{{ $appointment->patient->name }}</p>
                    <p class="text-sm text-zinc-500 line-clamp-1">{{ $appointment->reason }}</p>
                </div>
                <flux:badge :color="$appointment->type === 'teleconsultation' ? 'green' : 'blue'" size="sm">
                    {{ $appointment->type === 'teleconsultation' ? 'Online' : 'In-person' }}
                </flux:badge>
                <flux:badge :color="match($appointment->status) { 'confirmed' => 'green', 'pending' => 'yellow', 'completed' => 'blue', 'no_show' => 'red', default => 'zinc' }" size="sm">
                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                </flux:badge>
                <flux:button href="{{ route('doctor.appointments.show', $appointment->id) }}" wire:navigate size="sm" variant="ghost">View</flux:button>
                @if($appointment->status === 'confirmed' && $appointment->type === 'teleconsultation')
                    <flux:button href="{{ route('doctor.meeting', $appointment->id) }}" wire:navigate size="sm" variant="primary" icon="video-camera">Start</flux:button>
                @endif
            </div>
        @empty
            <div class="py-12 text-center text-zinc-500">No appointments for the selected date/status.</div>
        @endforelse
    </div>

    {{ $this->appointments->links() }}
</div>
