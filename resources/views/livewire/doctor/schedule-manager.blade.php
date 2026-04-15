<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">My Schedule</flux:heading>
        <flux:button wire:click="openCreate" variant="primary" icon="plus">Add Schedule</flux:button>
    </div>

    @if($showForm)
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-900 dark:bg-blue-950">
            <flux:heading size="lg" class="mb-4">{{ $editingId ? 'Edit Schedule' : 'New Schedule' }}</flux:heading>
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Day of Week</flux:label>
                    <flux:select wire:model="dayOfWeek">
                        @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $i => $day)
                            <option value="{{ $i }}">{{ $day }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="dayOfWeek" />
                </flux:field>
                <flux:field>
                    <flux:label>Appointment Type</flux:label>
                    <flux:select wire:model="appointmentType">
                        <option value="both">Both</option>
                        <option value="in_person">In-Person Only</option>
                        <option value="teleconsultation">Teleconsultation Only</option>
                    </flux:select>
                </flux:field>
                <flux:field>
                    <flux:label>Start Time</flux:label>
                    <flux:input type="time" wire:model="startTime" />
                    <flux:error name="startTime" />
                </flux:field>
                <flux:field>
                    <flux:label>End Time</flux:label>
                    <flux:input type="time" wire:model="endTime" />
                    <flux:error name="endTime" />
                </flux:field>
                <flux:field>
                    <flux:label>Slot Duration</flux:label>
                    <flux:select wire:model="slotDurationMinutes">
                        <option value="15">15 minutes</option>
                        <option value="20">20 minutes</option>
                        <option value="30">30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60">1 hour</option>
                    </flux:select>
                </flux:field>
                <flux:field class="flex items-end">
                    <flux:checkbox wire:model="isActive" label="Active" />
                </flux:field>
            </div>
            <div class="mt-4 flex gap-2">
                <flux:button wire:click="save" variant="primary">Save</flux:button>
                <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
            </div>
        </div>
    @endif

    <div class="space-y-3">
        @forelse($this->schedules as $schedule)
            <div class="flex items-center gap-4 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="w-28 font-semibold dark:text-white">{{ $schedule->day_name }}</div>
                <div class="flex-1 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ $schedule->start_time }} — {{ $schedule->end_time }} · {{ $schedule->slot_duration_minutes }}min slots · {{ ucfirst(str_replace('_', '-', $schedule->appointment_type)) }}
                </div>
                <flux:badge :color="$schedule->is_active ? 'green' : 'zinc'" size="sm">{{ $schedule->is_active ? 'Active' : 'Inactive' }}</flux:badge>
                <flux:button wire:click="edit({{ $schedule->id }})" size="sm" variant="ghost" icon="pencil">Edit</flux:button>
                <flux:button wire:click="delete({{ $schedule->id }})" wire:confirm="Delete this schedule?" size="sm" variant="danger" icon="trash">Delete</flux:button>
            </div>
        @empty
            <div class="rounded-xl border border-zinc-200 py-12 text-center dark:border-zinc-700">
                <flux:icon.clock class="mx-auto mb-3 size-12 text-zinc-300" />
                <p class="text-zinc-500">No schedules set. Add your availability above.</p>
            </div>
        @endforelse
    </div>
</div>
