<div class="flex items-center gap-4 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:avatar :name="$appointment->doctor->user->name" />

    <div class="flex-1 min-w-0">
        <p class="font-semibold text-zinc-900 dark:text-white">Dr. {{ $appointment->doctor->user->name }}</p>
        <p class="text-sm text-zinc-500">{{ $appointment->doctor->specialization->name }}</p>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
            {{ $appointment->scheduled_at->format('D, M d Y \a\t h:i A') }}
        </p>
    </div>

    <div class="flex flex-col items-end gap-2 shrink-0">
        <flux:badge :color="match($appointment->status) {
            'confirmed' => 'green',
            'pending' => 'yellow',
            'completed' => 'blue',
            'cancelled' => 'zinc',
            'no_show' => 'red',
            default => 'zinc',
        }" size="sm">
            {{ ucfirst($appointment->status) }}
        </flux:badge>
        <flux:badge :color="$appointment->type === 'teleconsultation' ? 'green' : 'blue'" size="sm" variant="outline">
            {{ $appointment->type === 'teleconsultation' ? 'Teleconsult' : 'In-Person' }}
        </flux:badge>
    </div>
</div>
