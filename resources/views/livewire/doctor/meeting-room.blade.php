<div class="mx-auto max-w-2xl space-y-6">
    <flux:heading size="xl">Meeting Room</flux:heading>

    <div class="rounded-xl border border-zinc-200 bg-white p-8 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center gap-4">
            <flux:avatar :name="$this->appointment->patient->name" size="md" />
            <div>
                <h2 class="text-xl font-bold dark:text-white">{{ $this->appointment->patient->name }}</h2>
                <p class="text-zinc-500">{{ $this->appointment->scheduled_at->format('M d, Y \a\t h:i A') }}</p>
                <p class="mt-1 text-sm text-zinc-500 line-clamp-1">{{ $this->appointment->reason }}</p>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <flux:button href="{{ $this->appointment->zoom_start_url }}" target="_blank" variant="primary" icon="video-camera">
                Start Meeting
            </flux:button>
        </div>

        @if($this->appointment->zoom_password)
            <p class="mt-2 text-sm text-zinc-500">Password: <code class="font-mono font-bold">{{ $this->appointment->zoom_password }}</code></p>
        @endif
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="lg" class="mb-4">Quick Notes</flux:heading>
        <flux:textarea wire:model.live="quickNote" rows="3" placeholder="Jot down quick notes during the consultation..." />

        <div class="mt-4 flex gap-2">
            <flux:button wire:click="markComplete" wire:confirm="Mark this appointment as complete?" variant="primary" icon="check">
                Mark Complete & Save Notes
            </flux:button>
            <flux:button href="{{ route('doctor.medical-notes', $this->appointment->id) }}" wire:navigate variant="ghost">
                Full Medical Notes
            </flux:button>
        </div>
    </div>
</div>
