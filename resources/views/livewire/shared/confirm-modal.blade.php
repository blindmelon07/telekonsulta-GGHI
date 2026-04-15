@teleport('body')
    @if($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50" wire:click="cancel"></div>
            <div class="relative z-10 w-full max-w-md rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-950">
                        <flux:icon.exclamation-triangle class="size-5 text-red-600" />
                    </div>
                    <flux:heading size="lg">{{ $title }}</flux:heading>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $message }}</p>
                <div class="mt-6 flex justify-end gap-3">
                    <flux:button wire:click="cancel" variant="ghost">{{ $cancelLabel }}</flux:button>
                    <flux:button wire:click="confirm" variant="danger">{{ $confirmLabel }}</flux:button>
                </div>
            </div>
        </div>
    @endif
@endteleport
