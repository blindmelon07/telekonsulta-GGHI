<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Medical Records</flux:heading>
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search records..." icon="magnifying-glass" />
    </div>

    <div class="space-y-4">
        @forelse($this->records as $record)
            <details class="group rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <summary class="flex cursor-pointer items-center gap-4 p-4">
                    <flux:icon.document-text class="size-5 text-zinc-400" />
                    <div class="flex-1">
                        <p class="font-semibold dark:text-white">{{ $record->appointment->scheduled_at->format('M d, Y') }}</p>
                        <p class="text-sm text-zinc-500">Dr. {{ $record->doctor->user->name }} — {{ $record->chief_complaint }}</p>
                    </div>
                    <flux:icon.chevron-down class="size-4 text-zinc-400 transition group-open:rotate-180" />
                </summary>

                <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                    <dl class="grid gap-4 sm:grid-cols-2">
                        @foreach(['diagnosis' => 'Diagnosis', 'prescription' => 'Prescription', 'lab_requests' => 'Lab Requests', 'notes' => 'Notes'] as $field => $label)
                            @if($record->$field)
                                <div>
                                    <dt class="text-sm font-medium text-zinc-500">{{ $label }}</dt>
                                    <dd class="mt-1 text-zinc-700 dark:text-zinc-300">{{ $record->$field }}</dd>
                                </div>
                            @endif
                        @endforeach
                        @if($record->follow_up_date)
                            <div>
                                <dt class="text-sm font-medium text-zinc-500">Follow-up</dt>
                                <dd class="mt-1 font-medium text-blue-600">{{ $record->follow_up_date->format('M d, Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </details>
        @empty
            <div class="rounded-xl border border-zinc-200 py-16 text-center dark:border-zinc-700">
                <flux:icon.document-text class="mx-auto mb-3 size-12 text-zinc-300" />
                <p class="text-zinc-500">No medical records found</p>
            </div>
        @endforelse
    </div>
</div>
