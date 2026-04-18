<div class="mx-auto max-w-2xl space-y-6" wire:poll.60s="refreshCountdown">
    <flux:heading size="xl">Teleconsultation Room</flux:heading>

    <div class="rounded-xl border border-zinc-200 bg-white p-8 text-center dark:border-zinc-700 dark:bg-zinc-900">
        <flux:avatar :name="$this->appointment->doctor->user->name" size="xl" class="mx-auto mb-4" />
        <h2 class="text-xl font-bold dark:text-white">{{ $this->appointment->doctor->user->name }}</h2>
        <p class="text-zinc-500">{{ $this->appointment->scheduled_at->format('M d, Y \a\t h:i A') }}</p>

        <div class="mt-6">
            @if($canJoin)
                <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700 dark:bg-green-950">
                    <p class="font-semibold">Your doctor is ready! You can join now.</p>
                </div>
                <flux:button href="{{ $this->appointment->zoom_join_url }}" target="_blank" variant="primary"  icon="video-camera">
                    Join Meeting
                </flux:button>
                @if($this->appointment->zoom_password)
                    <p class="mt-2 text-sm text-zinc-500">Meeting password: <code class="font-mono font-bold">{{ $this->appointment->zoom_password }}</code></p>
                @endif
            @else
                <div class="mb-4 rounded-lg bg-blue-50 p-4 text-blue-700 dark:bg-blue-950">
                    <p class="text-3xl font-bold">{{ $timeUntilMeeting }}</p>
                    <p class="mt-1 text-sm">Join button will appear 5 minutes before the appointment</p>
                </div>
                <flux:button disabled variant="primary" icon="clock">
                    Meeting Not Started Yet
                </flux:button>
            @endif
        </div>
    </div>
</div>
