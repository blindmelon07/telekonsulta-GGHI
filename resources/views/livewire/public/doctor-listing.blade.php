<div>
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 py-16 text-white">
        <div class="mx-auto max-w-7xl px-4">
            <h1 class="text-4xl font-bold">Find a Doctor</h1>
            <p class="mt-2 text-blue-100">Book in-person or teleconsultation appointments</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by doctor name..."
                    icon="magnifying-glass"
                    class="flex-1"
                />
                <flux:select wire:model.live="specialization" placeholder="All Specializations">
                    <option value="">All Specializations</option>
                    @foreach($this->specializations as $spec)
                        <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="type" placeholder="All Types">
                    <option value="">All Types</option>
                    <option value="teleconsultation">Teleconsultation</option>
                    <option value="in_person">In-Person</option>
                </flux:select>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($this->doctors as $doctor)
                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-center gap-3">
                        <flux:avatar :name="$doctor->user->name" size="lg" />
                        <div>
                            <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $doctor->user->name }}</h3>
                            <p class="text-sm text-zinc-500">{{ $doctor->specialization->name }}</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                        <p>{{ $doctor->experience_years }} years experience</p>
                        <p>In-person: <span class="font-medium text-zinc-900 dark:text-white">₱{{ number_format($doctor->consultation_fee / 100, 0) }}</span></p>
                        @if($doctor->is_available_online)
                            <p>Teleconsult: <span class="font-medium text-zinc-900 dark:text-white">₱{{ number_format($doctor->teleconsultation_fee / 100, 0) }}</span></p>
                        @endif
                    </div>

                    @if($doctor->is_available_online)
                        <flux:badge color="green" size="sm" class="mt-3">Online Available</flux:badge>
                    @endif

                    <div class="mt-4 flex gap-2">
                        <flux:button href="{{ route('doctors.show', $doctor->id) }}" wire:navigate variant="ghost" size="sm" class="flex-1">View</flux:button>
                        <flux:button href="{{ route('doctors.book', $doctor->id) }}" wire:navigate variant="primary" size="sm" class="flex-1">Book Now</flux:button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-zinc-500">
                    <flux:icon.user-minus class="mx-auto mb-4 size-12 opacity-40" />
                    <p>No doctors found matching your criteria.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $this->doctors->links() }}
        </div>
    </div>
</div>
