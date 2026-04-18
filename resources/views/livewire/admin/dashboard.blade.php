<div class="space-y-6">
    <flux:heading size="xl">Admin Dashboard</flux:heading>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Total Patients</p>
            <p class="mt-1 text-3xl font-bold dark:text-white">{{ $this->metrics['total_patients'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Active Doctors</p>
            <p class="mt-1 text-3xl font-bold dark:text-white">{{ $this->metrics['total_doctors'] }}</p>
        </div>
        <div class="rounded-xl border border-blue-100 bg-blue-50 p-6 dark:border-blue-900 dark:bg-blue-950">
            <p class="text-sm text-blue-600">Appointments Today</p>
            <p class="mt-1 text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $this->metrics['appointments_today'] }}</p>
        </div>
        <div class="rounded-xl border border-yellow-100 bg-yellow-50 p-6 dark:border-yellow-900 dark:bg-yellow-950">
            <p class="text-sm text-yellow-600">Pending Appointments</p>
            <p class="mt-1 text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $this->metrics['pending_appointments'] }}</p>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-6 dark:border-emerald-900 dark:bg-emerald-950">
            <p class="text-sm text-emerald-600">Revenue This Month</p>
            <p class="mt-1 text-3xl font-bold text-emerald-700 dark:text-emerald-300">₱{{ number_format($this->metrics['revenue_month'], 0) }}</p>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-6 dark:border-emerald-900 dark:bg-emerald-950">
            <p class="text-sm text-emerald-600">Total Revenue</p>
            <p class="mt-1 text-3xl font-bold text-emerald-700 dark:text-emerald-300">₱{{ number_format($this->metrics['total_revenue'], 0) }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 p-4 dark:border-zinc-700">
            <flux:heading size="lg">Recent Appointments</flux:heading>
        </div>
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @foreach($this->recentAppointments as $a)
                <div class="flex items-center gap-4 p-4">
                    <div class="flex-1">
                        <p class="text-sm font-semibold dark:text-white">{{ $a->patient->name }}</p>
                        <p class="text-xs text-zinc-500">with {{ $a->doctor->user->name }}</p>
                    </div>
                    <p class="text-sm text-zinc-500">{{ $a->scheduled_at->format('M d') }}</p>
                    <flux:badge :color="match($a->status) { 'confirmed' => 'green', 'pending' => 'yellow', 'completed' => 'blue', default => 'zinc' }" size="sm">{{ ucfirst($a->status) }}</flux:badge>
                </div>
            @endforeach
        </div>
    </div>
</div>
