<div class="mx-auto max-w-lg px-4 py-10">
    {{-- Step indicator --}}
    <div class="mb-8 flex items-center justify-between">
        @foreach(['Account', 'Personal', 'Medical', 'Verify'] as $i => $label)
            <div class="flex flex-col items-center">
                <div @class([
                    'flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold transition',
                    'bg-blue-600 text-white' => $step > $i,
                    'ring-2 ring-blue-600 bg-blue-600 text-white' => $step == $i + 1,
                    'bg-zinc-200 text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400' => $step < $i + 1,
                ])>
                    @if($step > $i)
                        <flux:icon.check class="size-4" />
                    @else
                        {{ $i + 1 }}
                    @endif
                </div>
                <span class="mt-1 text-xs text-zinc-500">{{ $label }}</span>
            </div>
            @if($i < 3)
                <div @class(['h-px flex-1 mx-2', 'bg-blue-600' => $step > $i + 1, 'bg-zinc-200 dark:bg-zinc-700' => $step <= $i + 1])></div>
            @endif
        @endforeach
    </div>

    <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="mb-6 text-center">
            <flux:heading size="xl">Create your account</flux:heading>
            <p class="mt-1 text-sm text-zinc-500">
                @if($step === 1) Enter your login credentials
                @elseif($step === 2) Tell us a bit about yourself
                @elseif($step === 3) Your medical profile (optional)
                @else Almost done!
                @endif
            </p>
        </div>

        {{-- Step 1: Account --}}
        @if($step === 1)
            <div class="space-y-4">
                <flux:field>
                    <flux:label>Full Name</flux:label>
                    <flux:input wire:model="name" type="text" placeholder="Juan Dela Cruz" autofocus />
                    <flux:error name="name" />
                </flux:field>
                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="juan@example.com" />
                    <flux:error name="email" />
                </flux:field>
                <flux:field>
                    <flux:label>Phone Number</flux:label>
                    <flux:input wire:model="phone" type="tel" placeholder="+63 912 345 6789" />
                    <flux:error name="phone" />
                </flux:field>
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
        @endif

        {{-- Step 2: Personal --}}
        @if($step === 2)
            <div class="space-y-4">
                <flux:field>
                    <flux:label>Date of Birth</flux:label>
                    <flux:input wire:model="dateOfBirth" type="date" :max="now()->subYear()->format('Y-m-d')" />
                    <flux:error name="dateOfBirth" />
                </flux:field>
                <flux:field>
                    <flux:label>Gender</flux:label>
                    <flux:select wire:model="gender">
                        <option value="">Select gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other / Prefer not to say</option>
                    </flux:select>
                    <flux:error name="gender" />
                </flux:field>
                <flux:field>
                    <flux:label>Address</flux:label>
                    <flux:textarea wire:model="address" rows="3" placeholder="Street, City, Province" />
                    <flux:error name="address" />
                </flux:field>
            </div>
        @endif

        {{-- Step 3: Medical (optional) --}}
        @if($step === 3)
            <div class="space-y-4">
                <p class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                    This information helps your doctor provide better care. You can skip or update this later.
                </p>
                <flux:field>
                    <flux:label>Blood Type</flux:label>
                    <flux:select wire:model="bloodType">
                        <option value="">Unknown / Skip</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
                <flux:field>
                    <flux:label>Known Allergies</flux:label>
                    <flux:textarea wire:model="allergies" rows="2" placeholder="e.g. Penicillin, Aspirin (leave blank if none)" />
                </flux:field>
                <flux:field>
                    <flux:label>Medical History</flux:label>
                    <flux:textarea wire:model="medicalHistory" rows="3" placeholder="e.g. Hypertension, Diabetes (leave blank if none)" />
                </flux:field>
            </div>
        @endif

        {{-- Step 4: Confirm --}}
        @if($step === 4)
            <div class="space-y-3 text-sm">
                <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                    <p class="mb-3 font-semibold text-zinc-700 dark:text-zinc-300">Review your information</p>
                    <dl class="space-y-2">
                        <div class="flex justify-between"><dt class="text-zinc-500">Name</dt><dd class="font-medium dark:text-white">{{ $name }}</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">Email</dt><dd class="font-medium dark:text-white">{{ $email }}</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">Phone</dt><dd class="font-medium dark:text-white">{{ $phone }}</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">Date of Birth</dt><dd class="font-medium dark:text-white">{{ \Carbon\Carbon::parse($dateOfBirth)->format('M d, Y') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">Gender</dt><dd class="font-medium dark:text-white">{{ ucfirst($gender) }}</dd></div>
                        @if($bloodType)<div class="flex justify-between"><dt class="text-zinc-500">Blood Type</dt><dd class="font-medium dark:text-white">{{ $bloodType }}</dd></div>@endif
                    </dl>
                </div>
                <p class="text-center text-xs text-zinc-500">
                    A verification email will be sent to <strong>{{ $email }}</strong> after registration.
                </p>
            </div>
        @endif

        {{-- Navigation --}}
        <div class="mt-6 flex justify-between gap-3">
            @if($step > 1)
                <flux:button wire:click="prevStep" variant="ghost">Back</flux:button>
            @else
                <div></div>
            @endif

            @if($step < 4)
                <flux:button wire:click="nextStep" variant="primary">Continue</flux:button>
            @else
                <flux:button
                    wire:click="register"
                    wire:loading.attr="disabled"
                    variant="primary"
                >
                    <span wire:loading.remove>Create Account</span>
                    <span wire:loading>Creating...</span>
                </flux:button>
            @endif
        </div>
    </div>

    <p class="mt-4 text-center text-sm text-zinc-500">
        Already have an account?
        <flux:link :href="route('login')" wire:navigate>Log in</flux:link>
    </p>
</div>
