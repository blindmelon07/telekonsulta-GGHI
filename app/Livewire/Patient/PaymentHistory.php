<?php

namespace App\Livewire\Patient;

use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Payment History')]
#[Layout('layouts.patient')]
class PaymentHistory extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    #[Url]
    public int $page = 1;

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function payments()
    {
        return Payment::with('appointment.doctor.user')
            ->where('patient_id', auth()->id())
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.payment-history');
    }
}
