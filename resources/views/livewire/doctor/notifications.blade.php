<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Notifications @if($unreadCount > 0)<flux:badge color="red" class="ml-2">{{ $unreadCount }}</flux:badge>@endif</flux:heading>
        @if($unreadCount > 0)<flux:button wire:click="markAllRead" variant="ghost" size="sm">Mark all read</flux:button>@endif
    </div>
    <div class="space-y-2">
        @forelse($this->notifications as $n)
            <div @class(['rounded-xl border p-4', 'border-blue-200 bg-blue-50 dark:border-blue-900 dark:bg-blue-950' => !$n->isRead(), 'border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900' => $n->isRead()])>
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <p class="font-semibold dark:text-white">{{ $n->title }}</p>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $n->message }}</p>
                        <p class="mt-2 text-xs text-zinc-400">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$n->isRead())<flux:button wire:click="markAsRead({{ $n->id }})" variant="ghost" size="xs">Mark read</flux:button>@endif
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-zinc-500"><flux:icon.bell-slash class="mx-auto mb-3 size-12 opacity-40" /><p>No notifications</p></div>
        @endforelse
    </div>
    {{ $this->notifications->links() }}
</div>
