<div class="mx-auto max-w-2xl px-4 py-10">
    <div class="mb-6 flex items-center gap-3">
        <flux:avatar :name="$this->doctor->user->name" />
        <div>
            <h1 class="text-xl font-bold dark:text-white">Book with {{ $this->doctor->user->name }}</h1>
            <p class="text-sm text-zinc-500">{{ $this->doctor->specialization->name }}</p>
        </div>
    </div>

    {{-- Step Progress --}}
    <div class="mb-8 flex items-center gap-2">
        @foreach(['Type', 'Date & Slot', 'Reason', 'Review'] as $i => $label)
            <div class="flex items-center gap-2">
                <div @class(['flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold', 'bg-blue-600 text-white' => $step > $i, 'bg-blue-600 text-white ring-2 ring-blue-300' => $step == $i + 1, 'bg-zinc-200 text-zinc-500 dark:bg-zinc-700' => $step < $i + 1])>
                    @if($step > $i) <flux:icon.check class="size-4" /> @else {{ $i + 1 }} @endif
                </div>
                <span class="hidden text-xs text-zinc-500 sm:block">{{ $label }}</span>
            </div>
            @if($i < 3)<div class="h-px flex-1 bg-zinc-200 dark:bg-zinc-700"></div>@endif
        @endforeach
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

        {{-- Step 1: Type --}}
        @if($step === 1)
            <h2 class="mb-4 text-lg font-semibold dark:text-white">Select Appointment Type</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div wire:click="$set('appointmentType', 'in_person')"
                    @class(['cursor-pointer rounded-lg border-2 p-4 transition', 'border-blue-500 bg-blue-50 dark:bg-blue-950' => $appointmentType === 'in_person', 'border-zinc-200 hover:border-zinc-300 dark:border-zinc-700' => $appointmentType !== 'in_person'])>
                    <flux:icon.building-office class="mb-2 size-8 text-blue-600" />
                    <p class="font-semibold dark:text-white">In-Person</p>
                    <p class="text-sm text-zinc-500">Visit the clinic</p>
                    <p class="mt-1 font-bold text-blue-600">₱{{ number_format($this->doctor->consultation_fee / 100, 0) }}</p>
                </div>
                @if($this->doctor->is_available_online)
                <div wire:click="$set('appointmentType', 'teleconsultation')"
                    @class(['cursor-pointer rounded-lg border-2 p-4 transition', 'border-green-500 bg-green-50 dark:bg-green-950' => $appointmentType === 'teleconsultation', 'border-zinc-200 hover:border-zinc-300 dark:border-zinc-700' => $appointmentType !== 'teleconsultation'])>
                    <flux:icon.video-camera class="mb-2 size-8 text-green-600" />
                    <p class="font-semibold dark:text-white">Teleconsultation</p>
                    <p class="text-sm text-zinc-500">Online via Zoom</p>
                    <p class="mt-1 font-bold text-green-600">₱{{ number_format($this->doctor->teleconsultation_fee / 100, 0) }}</p>
                </div>
                @endif
            </div>
        @endif

        {{-- Step 2: Date & Slot --}}
        @if($step === 2)
            <h2 class="mb-4 text-lg font-semibold dark:text-white">Select Date & Time</h2>
            <flux:field>
                <flux:label>Date</flux:label>
                <flux:input type="date" wire:model.live="selectedDate" :min="now()->addDay()->format('Y-m-d')" />
                <flux:error name="selectedDate" />
            </flux:field>

            @if($selectedDate)
                <div class="mt-4">
                    <flux:label>Available Slots</flux:label>
                    @if(count($this->slots) > 0)
                        <div class="mt-2 grid grid-cols-3 gap-2 sm:grid-cols-4">
                            @foreach($this->slots as $slot)
                                <button wire:click="$set('selectedSlot', '{{ $slot }}')"
                                    @class(['rounded-lg border py-2 text-sm font-medium transition', 'border-blue-500 bg-blue-600 text-white' => $selectedSlot === $slot, 'border-zinc-200 hover:border-blue-300 dark:border-zinc-700' => $selectedSlot !== $slot])>
                                    {{ $slot }}
                                </button>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-2 text-sm text-zinc-500">No slots available for this date.</p>
                    @endif
                </div>
            @endif
        @endif

        {{-- Step 3: Reason --}}
        @if($step === 3)
            <h2 class="mb-4 text-lg font-semibold dark:text-white">Reason for Visit</h2>
            <flux:field>
                <flux:label>Describe your concern</flux:label>
                <flux:textarea wire:model="reason" rows="4" placeholder="Please describe your symptoms or reason for the appointment..." />
                <flux:error name="reason" />
            </flux:field>
        @endif

        {{-- Step 4: Review --}}
        @if($step === 4)
            <h2 class="mb-4 text-lg font-semibold dark:text-white">Review & Confirm</h2>
            <dl class="space-y-3">
                <div class="flex justify-between text-sm">
                    <dt class="text-zinc-500">Doctor</dt>
                    <dd class="font-medium dark:text-white">{{ $this->doctor->user->name }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-zinc-500">Type</dt>
                    <dd class="font-medium dark:text-white">{{ ucfirst(str_replace('_', '-', $appointmentType)) }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-zinc-500">Date & Time</dt>
                    <dd class="font-medium dark:text-white">{{ \Carbon\Carbon::parse($selectedDate . ' ' . $selectedSlot)->format('M d, Y h:i A') }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-zinc-500">Fee</dt>
                    <dd class="font-bold text-blue-600">₱{{ number_format(($appointmentType === 'teleconsultation' ? $this->doctor->teleconsultation_fee : $this->doctor->consultation_fee) / 100, 0) }}</dd>
                </div>
                <div class="text-sm">
                    <dt class="text-zinc-500">Reason</dt>
                    <dd class="mt-1 font-medium dark:text-white">{{ $reason }}</dd>
                </div>
            </dl>
        @endif

        {{-- Navigation Buttons --}}
        <div class="mt-6 flex justify-between">
            @if($step > 1)
                <flux:button wire:click="prevStep" variant="ghost">Back</flux:button>
            @else
                <div></div>
            @endif

            @if($step < 4)
                <flux:button wire:click="nextStep" variant="primary">Continue</flux:button>
            @else
                <flux:button
                    wire:click="submitBooking"
                    wire:loading.attr="disabled"
                    variant="primary"
                >
                    <span wire:loading.remove>Confirm & Pay</span>
                    <span wire:loading>Processing...</span>
                </flux:button>
            @endif
        </div>
    </div>
</div>
