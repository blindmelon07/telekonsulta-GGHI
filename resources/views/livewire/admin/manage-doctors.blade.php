<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Manage Doctors</flux:heading>
        <div class="flex items-center gap-3">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." icon="magnifying-glass" class="w-64" />
            <flux:modal.trigger name="create-doctor">
                <flux:button variant="primary" icon="plus">Add Doctor</flux:button>
            </flux:modal.trigger>
        </div>
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
                    <tr wire:key="{{ $doctor->id }}">
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

    {{-- Add Doctor Modal --}}
    <flux:modal name="create-doctor" class="w-full max-w-2xl">
        <div class="space-y-6">
            <flux:heading size="lg">Add Doctor Account</flux:heading>

            <div class="space-y-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Account Details</p>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Full Name</flux:label>
                        <flux:input wire:model="name" placeholder="Dr. Juan Dela Cruz" />
                        <flux:error name="name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Phone Number</flux:label>
                        <flux:input wire:model="phone" placeholder="+63 912 345 6789" />
                        <flux:error name="phone" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="doctor@example.com" />
                    <flux:error name="email" />
                </flux:field>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Password</flux:label>
                        <flux:input wire:model="password" type="password" placeholder="Min. 8 characters" viewable />
                        <flux:error name="password" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Confirm Password</flux:label>
                        <flux:input wire:model="passwordConfirmation" type="password" placeholder="Repeat password" viewable />
                        <flux:error name="passwordConfirmation" />
                    </flux:field>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Professional Details</p>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Specialization</flux:label>
                        <flux:select wire:model="specializationId">
                            <option value="">Select specialization</option>
                            @foreach($this->specializations as $spec)
                                <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="specializationId" />
                    </flux:field>
                    <flux:field>
                        <flux:label>License Number</flux:label>
                        <flux:input wire:model="licenseNumber" placeholder="PRC License No." />
                        <flux:error name="licenseNumber" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Years of Experience</flux:label>
                        <flux:input wire:model="experienceYears" type="number" min="0" max="60" placeholder="0" />
                        <flux:error name="experienceYears" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Clinic Address</flux:label>
                        <flux:input wire:model="clinicAddress" placeholder="Optional" />
                        <flux:error name="clinicAddress" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Bio</flux:label>
                    <flux:textarea wire:model="bio" rows="3" placeholder="Brief professional background (optional)" />
                    <flux:error name="bio" />
                </flux:field>
            </div>

            <div class="space-y-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Fees (in PHP ₱)</p>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>In-Person Consultation Fee</flux:label>
                        <flux:input wire:model="consultationFee" type="number" min="0" step="0.01" placeholder="500.00" />
                        <flux:error name="consultationFee" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Teleconsultation Fee</flux:label>
                        <flux:input wire:model="teleconsultationFee" type="number" min="0" step="0.01" placeholder="400.00" :disabled="!$isAvailableOnline" />
                        <flux:error name="teleconsultationFee" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:checkbox wire:model.live="isAvailableOnline" label="Available for teleconsultation" />
                </flux:field>
            </div>

            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="createDoctor" variant="primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="createDoctor">Create Doctor</span>
                    <span wire:loading wire:target="createDoctor">Creating...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
