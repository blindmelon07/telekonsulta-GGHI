<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Appointment Oversight</flux:heading>
        <flux:button wire:click="exportCsv" variant="ghost" icon="arrow-down-tray" size="sm">Export CSV</flux:button>
    </div>
    <div class="flex flex-wrap gap-3">
        <flux:input type="date" wire:model.live="date" class="w-48" />
        <flux:select wire:model.live="status" placeholder="All Statuses" class="w-40">
            <option value="">All</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </flux:select>
        <flux:select wire:model.live="type" placeholder="All Types" class="w-40">
            <option value="">All</option>
            <option value="in_person">In-Person</option>
            <option value="teleconsultation">Teleconsultation</option>
        </flux:select>
    </div>
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <tr>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Patient</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Doctor</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Date</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Type</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Status</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Payment</th>
                    <th class="p-3 text-right font-semibold text-zinc-600 dark:text-zinc-400">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                @forelse($this->appointments as $a)
                    <tr>
                        <td class="p-3 dark:text-white">{{ $a->patient->name }}</td>
                        <td class="p-3 text-zinc-600 dark:text-zinc-400">{{ $a->doctor->user->name }}</td>
                        <td class="p-3 text-zinc-500">{{ $a->scheduled_at->format('M d, Y h:i A') }}</td>
                        <td class="p-3"><flux:badge :color="$a->type === 'teleconsultation' ? 'green' : 'blue'" size="sm">{{ ucfirst(str_replace('_','-',$a->type)) }}</flux:badge></td>
                        <td class="p-3"><flux:badge :color="match($a->status) { 'confirmed' => 'green', 'pending' => 'yellow', 'completed' => 'blue', default => 'zinc' }" size="sm">{{ ucfirst($a->status) }}</flux:badge></td>
                        <td class="p-3"><flux:badge :color="$a->payment_status === 'paid' ? 'green' : 'red'" size="sm">{{ ucfirst($a->payment_status) }}</flux:badge></td>
                        <td class="p-3 text-right font-medium dark:text-white">₱{{ number_format($a->amount/100, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-8 text-center text-zinc-500">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->appointments->links() }}
</div>
