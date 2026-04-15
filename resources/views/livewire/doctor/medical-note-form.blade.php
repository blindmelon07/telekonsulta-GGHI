<div class="mx-auto max-w-2xl space-y-6">
    <div class="flex items-center gap-3">
        <flux:button href="{{ route('doctor.appointment', $appointmentId) }}" wire:navigate variant="ghost" icon="arrow-left" size="sm">Back</flux:button>
        <flux:heading size="xl">Medical Notes</flux:heading>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="space-y-4">
            <flux:field>
                <flux:label>Chief Complaint <flux:badge color="red" size="sm">required</flux:badge></flux:label>
                <flux:textarea wire:model="chiefComplaint" rows="2" placeholder="Primary reason for the visit..." />
                <flux:error name="chiefComplaint" />
            </flux:field>
            <flux:field>
                <flux:label>Diagnosis <flux:badge color="red" size="sm">required</flux:badge></flux:label>
                <flux:textarea wire:model="diagnosis" rows="3" placeholder="Medical assessment and findings..." />
                <flux:error name="diagnosis" />
            </flux:field>
            <flux:field>
                <flux:label>Prescription</flux:label>
                <flux:textarea wire:model="prescription" rows="3" placeholder="Medications prescribed..." />
            </flux:field>
            <flux:field>
                <flux:label>Lab Requests</flux:label>
                <flux:textarea wire:model="labRequests" rows="2" placeholder="Laboratory tests ordered..." />
            </flux:field>
            <flux:field>
                <flux:label>Additional Notes</flux:label>
                <flux:textarea wire:model="notes" rows="3" placeholder="Any additional notes..." />
            </flux:field>
            <flux:field>
                <flux:label>Follow-up Date</flux:label>
                <flux:input type="date" wire:model="followUpDate" :min="now()->addDay()->format('Y-m-d')" />
            </flux:field>
        </div>

        <flux:button wire:click="save" variant="primary" class="mt-6" icon="check">Save Medical Notes</flux:button>
    </div>
</div>
