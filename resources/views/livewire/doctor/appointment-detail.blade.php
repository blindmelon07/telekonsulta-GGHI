<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center gap-3">
        <flux:button href="{{ route('doctor.appointments') }}" wire:navigate variant="ghost" icon="arrow-left" size="sm">Back</flux:button>
        <flux:heading size="xl">Appointment Detail</flux:heading>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-start gap-4">
            <flux:avatar :name="$this->appointment->patient->name" size="lg" />
            <div class="flex-1">
                <h2 class="text-xl font-bold dark:text-white">{{ $this->appointment->patient->name }}</h2>
                <p class="text-zinc-500">{{ $this->appointment->patient->email }}</p>
                @if($this->appointment->patient->patientProfile?->blood_type)
                    <p class="text-sm text-zinc-500 mt-1">Blood type: {{ $this->appointment->patient->patientProfile->blood_type }}</p>
                @endif
            </div>
            <flux:badge :color="match($this->appointment->status) { 'confirmed' => 'green', 'pending' => 'yellow', 'completed' => 'blue', default => 'zinc' }" size="lg">
                {{ ucfirst($this->appointment->status) }}
            </flux:badge>
        </div>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-zinc-500">Date & Time</dt><dd class="font-semibold dark:text-white">{{ $this->appointment->scheduled_at->format('D, M d Y \a\t h:i A') }}</dd></div>
            <div><dt class="text-sm text-zinc-500">Type</dt><dd class="font-semibold dark:text-white">{{ ucfirst(str_replace('_', '-', $this->appointment->type)) }}</dd></div>
            <div><dt class="text-sm text-zinc-500">Payment</dt><dd><flux:badge :color="$this->appointment->payment_status === 'paid' ? 'green' : 'red'">{{ ucfirst($this->appointment->payment_status) }}</flux:badge></dd></div>
        </dl>

        @if($this->appointment->reason)
            <div class="mt-4 rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                <p class="text-sm font-medium text-zinc-500">Reason for Visit</p>
                <p class="mt-1 text-zinc-700 dark:text-zinc-300">{{ $this->appointment->reason }}</p>
            </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-2">
            @if($this->appointment->status === 'pending')
                <flux:button wire:click="confirm" variant="primary">Confirm Appointment</flux:button>
            @endif
            @if(in_array($this->appointment->status, ['confirmed', 'pending']))
                <flux:button wire:click="complete" variant="primary" icon="check">Mark Complete</flux:button>
                <flux:button wire:click="cancel" wire:confirm="Cancel this appointment?" variant="danger">Cancel</flux:button>
            @endif
            <flux:button href="{{ route('doctor.medical-notes', $this->appointment->id) }}" wire:navigate variant="ghost" icon="document-text">
                {{ $this->appointment->medicalRecord ? 'Edit Notes' : 'Add Medical Notes' }}
            </flux:button>
            @if($this->appointment->type === 'teleconsultation' && $this->appointment->zoom_start_url)
                <flux:button href="{{ route('doctor.meeting', $this->appointment->id) }}" wire:navigate variant="primary" icon="video-camera">Start Meeting</flux:button>
            @endif
        </div>
    </div>
</div>
