<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center gap-3">
        <flux:button href="{{ route('patient.appointments') }}" wire:navigate variant="ghost" icon="arrow-left" size="sm">Back</flux:button>
        <flux:heading size="xl">Appointment Details</flux:heading>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-start gap-4">
            <flux:avatar :name="$this->appointment->doctor->user->name" size="lg" />
            <div>
                <h2 class="text-xl font-bold dark:text-white">Dr. {{ $this->appointment->doctor->user->name }}</h2>
                <p class="text-zinc-500">{{ $this->appointment->doctor->specialization->name }}</p>
            </div>
            <div class="ml-auto">
                <flux:badge :color="match($this->appointment->status) { 'confirmed' => 'green', 'pending' => 'yellow', 'completed' => 'blue', 'cancelled' => 'red', default => 'zinc' }" size="lg">
                    {{ ucfirst($this->appointment->status) }}
                </flux:badge>
            </div>
        </div>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-zinc-500">Date & Time</dt>
                <dd class="font-semibold dark:text-white">{{ $this->appointment->scheduled_at->format('D, M d Y \a\t h:i A') }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-500">Type</dt>
                <dd class="font-semibold dark:text-white">{{ ucfirst(str_replace('_', '-', $this->appointment->type)) }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-500">Payment</dt>
                <dd>
                    <flux:badge :color="$this->appointment->payment_status === 'paid' ? 'green' : 'red'">
                        {{ ucfirst($this->appointment->payment_status) }}
                    </flux:badge>
                </dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-500">Fee</dt>
                <dd class="font-semibold text-blue-600">₱{{ number_format($this->appointment->amount / 100, 2) }}</dd>
            </div>
        </dl>

        @if($this->appointment->reason)
            <div class="mt-4">
                <dt class="text-sm text-zinc-500">Reason</dt>
                <dd class="mt-1 text-zinc-700 dark:text-zinc-300">{{ $this->appointment->reason }}</dd>
            </div>
        @endif

        {{-- Teleconsultation join button --}}
        @if($this->appointment->type === 'teleconsultation' && $this->appointment->status === 'confirmed' && $this->appointment->zoom_join_url)
            <div class="mt-6">
                <flux:button href="{{ route('patient.teleconsultations', $this->appointment->id) }}" wire:navigate variant="primary" icon="video-camera">
                    Join Teleconsultation
                </flux:button>
            </div>
        @endif

        {{-- Pay now if unpaid --}}
        @if($this->appointment->payment_status === 'unpaid' && !$this->appointment->isCancelled())
            <div class="mt-6">
                <flux:button href="{{ route('patient.checkout', $this->appointment->id) }}" wire:navigate variant="primary" icon="credit-card">
                    Complete Payment
                </flux:button>
            </div>
        @endif

        {{-- Cancel button --}}
        @if(in_array($this->appointment->status, ['pending', 'confirmed']))
            <div class="mt-4">
                <flux:button wire:click="cancel" wire:confirm="Are you sure you want to cancel this appointment?" variant="danger" size="sm">
                    Cancel Appointment
                </flux:button>
            </div>
        @endif
    </div>

    {{-- Medical Record --}}
    @if($this->appointment->medicalRecord)
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">Medical Record</flux:heading>
            <dl class="space-y-3">
                @foreach(['chief_complaint' => 'Chief Complaint', 'diagnosis' => 'Diagnosis', 'prescription' => 'Prescription', 'lab_requests' => 'Lab Requests', 'notes' => 'Notes'] as $field => $label)
                    @if($this->appointment->medicalRecord->$field)
                        <div>
                            <dt class="text-sm text-zinc-500">{{ $label }}</dt>
                            <dd class="mt-1 text-zinc-700 dark:text-zinc-300">{{ $this->appointment->medicalRecord->$field }}</dd>
                        </div>
                    @endif
                @endforeach
                @if($this->appointment->medicalRecord->follow_up_date)
                    <div>
                        <dt class="text-sm text-zinc-500">Follow-up Date</dt>
                        <dd class="mt-1 font-medium text-blue-600">{{ $this->appointment->medicalRecord->follow_up_date->format('M d, Y') }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    @endif
</div>
