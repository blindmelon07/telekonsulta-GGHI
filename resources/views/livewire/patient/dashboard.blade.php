<div class="space-y-6">
    <flux:heading size="xl">Welcome back, {{ auth()->user()->name }}!</flux:heading>

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Total Appointments</p>
            <p class="mt-1 text-3xl font-bold dark:text-white">{{ $this->stats['total'] }}</p>
        </div>
        <div class="rounded-xl border border-blue-100 bg-blue-50 p-6 dark:border-blue-900 dark:bg-blue-950">
            <p class="text-sm text-blue-600">Upcoming</p>
            <p class="mt-1 text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $this->stats['upcoming'] }}</p>
        </div>
        <div class="rounded-xl border border-green-100 bg-green-50 p-6 dark:border-green-900 dark:bg-green-950">
            <p class="text-sm text-green-600">Completed</p>
            <p class="mt-1 text-3xl font-bold text-green-700 dark:text-green-300">{{ $this->stats['completed'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500">Cancelled</p>
            <p class="mt-1 text-3xl font-bold dark:text-white">{{ $this->stats['cancelled'] }}</p>
        </div>
    </div>

    {{-- Upcoming Appointments --}}
    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center justify-between border-b border-zinc-200 p-4 dark:border-zinc-700">
            <flux:heading size="lg">Upcoming Appointments</flux:heading>
            <flux:button href="{{ route('patient.appointments') }}" wire:navigate size="sm" variant="ghost">View All</flux:button>
        </div>

        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($this->upcomingAppointments as $appointment)
                <div class="flex items-center gap-4 p-4">
                    <flux:avatar :name="$appointment->doctor->user->name" />
                    <div class="flex-1">
                        <p class="font-medium dark:text-white">Dr. {{ $appointment->doctor->user->name }}</p>
                        <p class="text-sm text-zinc-500">{{ $appointment->doctor->specialization->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium dark:text-white">{{ $appointment->scheduled_at->format('M d, Y') }}</p>
                        <p class="text-sm text-zinc-500">{{ $appointment->scheduled_at->format('h:i A') }}</p>
                    </div>
                    <flux:badge :color="match($appointment->type) { 'teleconsultation' => 'green', default => 'blue' }">
                        {{ ucfirst(str_replace('_', '-', $appointment->type)) }}
                    </flux:badge>
                    <flux:button href="{{ route('patient.appointment', $appointment->id) }}" wire:navigate size="sm" variant="ghost">View</flux:button>
                </div>
            @empty
                <div class="p-8 text-center text-zinc-500">
                    <flux:icon.calendar class="mx-auto mb-3 size-12 opacity-40" />
                    <p>No upcoming appointments</p>
                    <flux:button href="{{ route('doctors.index') }}" wire:navigate class="mt-3" size="sm" variant="primary">Book Now</flux:button>
                </div>
            @endforelse
        </div>
    </div>
</div>
