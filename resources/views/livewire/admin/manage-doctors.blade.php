<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Manage Doctors</flux:heading>
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." icon="magnifying-glass" class="w-64" />
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full">
            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <tr>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Doctor</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Specialization</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Status</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                @forelse($this->doctors as $doctor)
                    <tr>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$doctor->user->name" size="sm" />
                                <div>
                                    <p class="text-sm font-medium dark:text-white">{{ $doctor->user->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $doctor->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $doctor->specialization->name }}</td>
                        <td class="p-4">
                            <flux:badge :color="$doctor->is_active ? 'green' : 'red'" size="sm">{{ $doctor->is_active ? 'Active' : 'Inactive' }}</flux:badge>
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <flux:button wire:click="toggleActive({{ $doctor->id }})" size="sm" variant="ghost">
                                    {{ $doctor->is_active ? 'Deactivate' : 'Activate' }}
                                </flux:button>
                                <flux:button wire:click="delete({{ $doctor->id }})" wire:confirm="Delete Dr. {{ $doctor->user->name }}? This cannot be undone." size="sm" variant="danger">Delete</flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-8 text-center text-zinc-500">No doctors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->doctors->links() }}
</div>
