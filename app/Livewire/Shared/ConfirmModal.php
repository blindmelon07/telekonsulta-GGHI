<?php

namespace App\Livewire\Shared;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmModal extends Component
{
    public bool $show = false;

    public string $title = 'Are you sure?';

    public string $message = 'This action cannot be undone.';

    public string $confirmLabel = 'Confirm';

    public string $cancelLabel = 'Cancel';

    public string $eventName = '';

    /** @var array<string, mixed> */
    public array $eventData = [];

    #[On('open-confirm-modal')]
    public function open(
        string $title,
        string $message,
        string $eventName,
        string $confirmLabel = 'Confirm',
        string $cancelLabel = 'Cancel',
        array $eventData = []
    ): void {
        $this->title = $title;
        $this->message = $message;
        $this->eventName = $eventName;
        $this->confirmLabel = $confirmLabel;
        $this->cancelLabel = $cancelLabel;
        $this->eventData = $eventData;
        $this->show = true;
    }

    public function confirm(): void
    {
        $this->show = false;
        $this->dispatch($this->eventName, ...$this->eventData);
    }

    public function cancel(): void
    {
        $this->show = false;
    }

    public function render(): View
    {
        return view('livewire.shared.confirm-modal');
    }
}
