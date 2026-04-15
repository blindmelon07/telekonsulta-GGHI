<div class="mx-auto max-w-2xl space-y-6">
    <flux:heading size="xl">System Settings</flux:heading>

    {{-- Logo --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="lg" class="mb-4">App Logo</flux:heading>

        <div class="mb-4 flex items-center gap-4">
            @if($currentLogoUrl)
                <img src="{{ $currentLogoUrl }}" alt="App Logo" class="h-16 w-auto rounded-lg border border-zinc-200 object-contain p-1 dark:border-zinc-700">
            @else
                <div class="flex h-16 w-16 items-center justify-center rounded-lg border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800">
                    <flux:icon.photo class="size-7 text-zinc-400" />
                </div>
            @endif

            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                @if($currentLogoUrl)
                    <p class="font-medium text-zinc-700 dark:text-zinc-200">Logo uploaded</p>
                    <p>PNG, JPG, SVG, or WebP &bull; max 2 MB</p>
                @else
                    <p class="font-medium text-zinc-700 dark:text-zinc-200">No logo set</p>
                    <p>PNG, JPG, SVG, or WebP &bull; max 2 MB</p>
                @endif
            </div>
        </div>

        <div
            x-data="{ uploading: false, progress: 0 }"
            x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            class="space-y-3"
        >
            @if($logo)
                <div class="flex items-center gap-3">
                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="h-14 w-auto rounded-lg border border-zinc-200 object-contain p-1 dark:border-zinc-700">
                    <p class="text-sm text-zinc-500">Preview — click "Save Logo" to apply.</p>
                </div>
            @endif

            <div x-show="uploading" class="w-full">
                <div class="h-1.5 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                    <div class="h-full rounded-full bg-blue-500 transition-all" :style="'width: ' + progress + '%'"></div>
                </div>
                <p class="mt-1 text-xs text-zinc-500" x-text="progress + '% uploaded'"></p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <label class="cursor-pointer">
                    <input type="file" wire:model="logo" accept="image/*" class="sr-only">
                    <flux:button tag="span" variant="ghost" icon="arrow-up-tray" size="sm">
                        Choose Image
                    </flux:button>
                </label>

                @if($logo)
                    <flux:button wire:click="saveLogo" variant="primary" size="sm">
                        Save Logo
                    </flux:button>
                @endif

                @if($currentLogoUrl)
                    <flux:button
                        wire:click="removeLogo"
                        wire:confirm="Remove the current logo?"
                        variant="danger"
                        size="sm"
                    >
                        Remove
                    </flux:button>
                @endif
            </div>

            <flux:error name="logo" />
        </div>
    </div>

    {{-- Application Name --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="lg" class="mb-4">Application</flux:heading>
        <flux:field>
            <flux:label>App Name</flux:label>
            <flux:input wire:model="appName" />
            <flux:error name="appName" />
        </flux:field>
        <flux:button wire:click="save" variant="primary" class="mt-4">Save Settings</flux:button>
    </div>

    {{-- Maintenance Mode --}}
    <div class="rounded-xl border border-red-200 bg-red-50 p-6 dark:border-red-900 dark:bg-red-950">
        <flux:heading size="lg" class="mb-2 text-red-700 dark:text-red-400">Maintenance Mode</flux:heading>
        <p class="mb-4 text-sm text-red-600 dark:text-red-400">
            @if($maintenanceMode)
                The application is currently in <strong>maintenance mode</strong>. Only administrators can access it.
            @else
                The application is running normally.
            @endif
        </p>
        <flux:button
            wire:click="toggleMaintenance"
            wire:confirm="{{ $maintenanceMode ? 'Bring the application back online?' : 'Put the application in maintenance mode?' }}"
            :variant="$maintenanceMode ? 'primary' : 'danger'"
        >
            {{ $maintenanceMode ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode' }}
        </flux:button>
    </div>
</div>
