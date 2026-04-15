<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">My Earnings</flux:heading>
        <div class="flex gap-2">
            @foreach(['week' => 'Week', 'month' => 'Month', 'year' => 'Year'] as $value => $label)
                <flux:button wire:click="$set('period', '{{ $value }}')" :variant="$period === $value ? 'primary' : 'ghost'" size="sm">{{ $label }}</flux:button>
            @endforeach
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-6 dark:border-emerald-900 dark:bg-emerald-950">
            <p class="text-sm text-emerald-600">Total Earnings</p>
            <p class="mt-1 text-3xl font-bold text-emerald-700 dark:text-emerald-300">₱{{ number_format($this->summary['total'], 0) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Appointments</p>
            <p class="mt-1 text-3xl font-bold dark:text-white">{{ $this->summary['count'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Average per Visit</p>
            <p class="mt-1 text-3xl font-bold dark:text-white">₱{{ number_format($this->summary['average'], 0) }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full">
            <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <tr>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Patient</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Date</th>
                    <th class="p-4 text-left text-sm font-semibold text-zinc-600 dark:text-zinc-400">Method</th>
                    <th class="p-4 text-right text-sm font-semibold text-zinc-600 dark:text-zinc-400">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                @forelse($this->summary['payments'] as $payment)
                    <tr>
                        <td class="p-4 text-sm dark:text-white">{{ $payment->appointment->patient->name }}</td>
                        <td class="p-4 text-sm text-zinc-500">{{ $payment->paid_at->format('M d, Y') }}</td>
                        <td class="p-4 text-sm uppercase text-zinc-500">{{ $payment->method }}</td>
                        <td class="p-4 text-right text-sm font-semibold text-emerald-600">₱{{ number_format($payment->amount / 100, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-8 text-center text-zinc-500">No earnings for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
