<div class="mx-auto max-w-4xl px-4 py-10">
    <div class="rounded-xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
            <flux:avatar :name="$this->doctor->user->name" size="xl" class="shrink-0" />
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $this->doctor->user->name }}</h1>
                <p class="mt-1 text-lg text-blue-600">{{ $this->doctor->specialization->name }}</p>
                <p class="mt-1 text-zinc-500">{{ $this->doctor->experience_years }} years of experience</p>
                <p class="mt-1 text-sm text-zinc-500">License: {{ $this->doctor->license_number }}</p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <flux:badge color="blue">In-person: ₱{{ number_format($this->doctor->consultation_fee / 100, 0) }}</flux:badge>
                    @if($this->doctor->is_available_online)
                        <flux:badge color="green">Teleconsult: ₱{{ number_format($this->doctor->teleconsultation_fee / 100, 0) }}</flux:badge>
                    @endif
                </div>

                @if($this->doctor->bio)
                    <p class="mt-4 text-zinc-600 dark:text-zinc-400">{{ $this->doctor->bio }}</p>
                @endif

                @if($this->doctor->clinic_address)
                    <p class="mt-3 flex items-center gap-1 text-sm text-zinc-500">
                        <flux:icon.map-pin class="size-4" /> {{ $this->doctor->clinic_address }}
                    </p>
                @endif
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <flux:button href="{{ route('doctors.book', $this->doctor->id) }}" wire:navigate variant="primary" icon="calendar">
                Book Appointment
            </flux:button>
            <flux:button href="{{ route('doctors.index') }}" wire:navigate variant="ghost">
                Back to Doctors
            </flux:button>
        </div>
    </div>
</div>
