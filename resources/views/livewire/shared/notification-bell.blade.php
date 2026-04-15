<div class="relative">
    <button wire:click="$toggle('open')" class="relative rounded-full p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">
        <flux:icon.bell class="size-5 text-zinc-600 dark:text-zinc-400" />
        @if($unreadCount > 0)
            <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    @if($open)
        <div class="absolute right-0 top-10 z-50 w-80 rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-zinc-200 px-4 py-3 dark:border-zinc-700">
                <p class="font-semibold dark:text-white">Notifications</p>
                @if($unreadCount > 0)
                    <button wire:click="markAllRead" class="text-xs text-blue-600 hover:underline">Mark all read</button>
                @endif
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($notifications as $n)
                    <div @class(['px-4 py-3 text-sm border-b border-zinc-100 dark:border-zinc-800', 'bg-blue-50 dark:bg-blue-950' => !$n->isRead()])>
                        <p class="font-medium dark:text-white">{{ $n->title }}</p>
                        <p class="text-zinc-500">{{ Str::limit($n->message, 80) }}</p>
                        <p class="mt-1 text-xs text-zinc-400">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-zinc-500">No notifications</div>
                @endforelse
            </div>
        </div>
        <div wire:click="$set('open', false)" class="fixed inset-0 z-40"></div>
    @endif
</div>
