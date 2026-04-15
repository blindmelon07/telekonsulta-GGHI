<?php

namespace App\Livewire\Shared;

use App\Models\AppNotification;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public bool $open = false;

    public function mount(): void
    {
        $this->unreadCount = auth()->user()->unreadNotificationsCount();
    }

    public function getNotifications()
    {
        return AppNotification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->take(10)
            ->get();
    }

    public function refreshCount(): void
    {
        $this->unreadCount = auth()->user()->unreadNotificationsCount();
    }

    #[Renderless]
    public function markAsRead(int $notificationId): void
    {
        AppNotification::where('id', $notificationId)
            ->where('user_id', auth()->id())
            ->first()?->markAsRead();

        $this->unreadCount = max(0, $this->unreadCount - 1);
    }

    public function markAllRead(): void
    {
        AppNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->unreadCount = 0;
    }

    #[On('notification-received')]
    public function onNotificationReceived(): void
    {
        $this->unreadCount++;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.shared.notification-bell', [
            'notifications' => $this->open ? $this->getNotifications() : collect(),
        ]);
    }
}
