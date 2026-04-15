<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Payment History</flux:heading>
        <flux:select wire:model.live="status" placeholder="All Statuses">
            <option value="">All Statuses</option>
            <option value="paid">Paid</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
        </flux:select>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full">
            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <tr>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Date</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Doctor</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Method</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Amount</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                @forelse($this->payments as $payment)
                    <tr>
                        <td class="p-4 text-sm dark:text-white">{{ $payment->created_at->format('M d, Y') }}</td>
                        <td class="p-4 text-sm dark:text-white">Dr. {{ $payment->appointment->doctor->user->name }}</td>
                        <td class="p-4 text-sm uppercase text-zinc-500">{{ $payment->method }}</td>
                        <td class="p-4 text-sm font-semibold dark:text-white">₱{{ number_format($payment->amount / 100, 2) }}</td>
                        <td class="p-4">
                            <flux:badge :color="match($payment->status) { 'paid' => 'green', 'pending' => 'yellow', 'failed' => 'red', 'refunded' => 'zinc', default => 'zinc' }" size="sm">
                                {{ ucfirst($payment->status) }}
                            </flux:badge>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-8 text-center text-zinc-500">No payments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $this->payments->links() }}
</div>
