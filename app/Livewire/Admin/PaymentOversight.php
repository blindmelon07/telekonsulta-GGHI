<?php

namespace App\Livewire\Admin;

use App\Jobs\ProcessPayMongoRefundJob;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Payment Oversight')]
#[Layout('layouts.admin')]
class PaymentOversight extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function payments()
    {
        return Payment::with('patient', 'appointment.doctor.user')
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function refund(int $paymentId): void
    {
        ProcessPayMongoRefundJob::dispatch($paymentId);
        $this->dispatch('notify', message: 'Refund job queued.', type: 'success');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.payment-oversight');
    }
}
