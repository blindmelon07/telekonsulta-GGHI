<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Manage Patients</flux:heading>
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." icon="magnifying-glass" class="w-64" />
    </div>
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full">
            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <tr>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Patient</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Joined</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Status</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                @forelse($this->patients as $patient)
                    <tr>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$patient->name" size="sm" />
                                <div>
                                    <p class="text-sm font-medium dark:text-white">{{ $patient->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $patient->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-sm text-zinc-500">{{ $patient->created_at->format('M d, Y') }}</td>
                        <td class="p-4"><flux:badge :color="$patient->is_active ? 'green' : 'red'" size="sm">{{ $patient->is_active ? 'Active' : 'Inactive' }}</flux:badge></td>
                        <td class="p-4">
                            <flux:button wire:click="toggleActive({{ $patient->id }})" size="sm" variant="ghost">
                                {{ $patient->is_active ? 'Deactivate' : 'Activate' }}
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-8 text-center text-zinc-500">No patients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->patients->links() }}
</div>
