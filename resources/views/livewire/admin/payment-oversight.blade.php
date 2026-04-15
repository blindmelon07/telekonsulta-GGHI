<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Payment Oversight</flux:heading>
        <flux:select wire:model.live="status" placeholder="All Statuses" class="w-48">
            <option value="">All</option>
            <option value="paid">Paid</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
        </flux:select>
    </div>
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-sm">
            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <tr>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Patient</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Doctor</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Method</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Date</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Status</th>
                    <th class="p-3 text-right font-semibold text-zinc-600 dark:text-zinc-400">Amount</th>
                    <th class="p-3 text-left font-semibold text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                @forelse($this->payments as $p)
                    <tr>
                        <td class="p-3 dark:text-white">{{ $p->patient->name }}</td>
                        <td class="p-3 text-zinc-600 dark:text-zinc-400">{{ $p->appointment->doctor->user->name }}</td>
                        <td class="p-3 uppercase text-zinc-500">{{ $p->method }}</td>
                        <td class="p-3 text-zinc-500">{{ $p->created_at->format('M d, Y') }}</td>
                        <td class="p-3"><flux:badge :color="match($p->status) { 'paid' => 'green', 'pending' => 'yellow', 'failed' => 'red', default => 'zinc' }" size="sm">{{ ucfirst($p->status) }}</flux:badge></td>
                        <td class="p-3 text-right font-medium dark:text-white">₱{{ number_format($p->amount/100, 2) }}</td>
                        <td class="p-3">
                            @if($p->status === 'paid')
                                <flux:button wire:click="refund({{ $p->id }})" wire:confirm="Process refund for ₱{{ number_format($p->amount/100, 2) }}?" size="sm" variant="danger">Refund</flux:button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-8 text-center text-zinc-500">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $this->payments->links() }}
</div>
