<?php

namespace App\Livewire\Patient;

use App\Models\AppNotification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Notifications')]
#[Layout('layouts.patient')]
class Notifications extends Component
{
    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->unreadCount = auth()->user()->unreadNotificationsCount();
    }

    #[Computed]
    public function notifications()
    {
        return AppNotification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function refreshUnreadCount(): void
    {
        $this->unreadCount = auth()->user()->unreadNotificationsCount();
    }

    #[Renderless]
    public function markAsRead(int $notificationId): void
    {
        AppNotification::where('id', $notificationId)
            ->where('user_id', auth()->id())
            ->first()?->markAsRead();

        $this->unreadCount = auth()->user()->unreadNotificationsCount();
    }

    public function markAllRead(): void
    {
        AppNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->unreadCount = 0;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.notifications');
    }
}
