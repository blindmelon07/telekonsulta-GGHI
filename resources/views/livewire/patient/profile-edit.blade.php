<div class="mx-auto max-w-2xl space-y-6">
    <flux:heading size="xl">My Profile</flux:heading>

    {{-- Tab Nav --}}
    <div class="flex gap-1 rounded-lg border border-zinc-200 bg-zinc-50 p-1 dark:border-zinc-700 dark:bg-zinc-900">
        <button wire:click="$set('activeTab', 'personal')" @class(['flex-1 rounded-md py-2 text-sm font-medium transition', 'bg-white shadow dark:bg-zinc-800' => $activeTab === 'personal', 'text-zinc-500' => $activeTab !== 'personal'])>Personal Info</button>
        <button wire:click="$set('activeTab', 'medical')" @class(['flex-1 rounded-md py-2 text-sm font-medium transition', 'bg-white shadow dark:bg-zinc-800' => $activeTab === 'medical', 'text-zinc-500' => $activeTab !== 'medical'])>Medical Info</button>
    </div>

    @if($activeTab === 'personal')
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Full Name</flux:label>
                    <flux:input wire:model="name" />
                    <flux:error name="name" />
                </flux:field>
                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input type="email" wire:model="email" />
                    <flux:error name="email" />
                </flux:field>
                <flux:field>
                    <flux:label>Phone</flux:label>
                    <flux:input wire:model="phone" placeholder="+63..." />
                </flux:field>
                <flux:field>
                    <flux:label>Date of Birth</flux:label>
                    <flux:input type="date" wire:model="dateOfBirth" />
                </flux:field>
                <flux:field>
                    <flux:label>Gender</flux:label>
                    <flux:select wire:model="gender">
                        <option value="">Select...</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </flux:select>
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Address</flux:label>
                    <flux:textarea wire:model="address" rows="2" />
                </flux:field>
            </div>
            <flux:button wire:click="savePersonal" variant="primary" class="mt-4">Save Personal Info</flux:button>
        </div>
    @endif

    @if($activeTab === 'medical')
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Blood Type</flux:label>
                    <flux:select wire:model="bloodType">
                        <option value="">Select...</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                            <option value="{{ $bt }}">{{ $bt }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
                <flux:field>
                    <flux:label>PhilHealth Number</flux:label>
                    <flux:input wire:model="philhealthNumber" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Allergies</flux:label>
                    <flux:textarea wire:model="allergies" rows="2" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Medical History</flux:label>
                    <flux:textarea wire:model="medicalHistory" rows="3" />
                </flux:field>
                <flux:field>
                    <flux:label>Emergency Contact Name</flux:label>
                    <flux:input wire:model="emergencyContactName" />
                </flux:field>
                <flux:field>
                    <flux:label>Emergency Contact Phone</flux:label>
                    <flux:input wire:model="emergencyContactPhone" />
                </flux:field>
            </div>
            <flux:button wire:click="saveMedical" variant="primary" class="mt-4">Save Medical Info</flux:button>
        </div>
    @endif
</div>
