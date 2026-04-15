<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Status')]
#[Layout('layouts.patient')]
class PaymentStatus extends Component
{
    #[Locked]
    public int $appointmentId;

    public function mount(int $appointmentId): void
    {
        $appointment = Appointment::where('id', $appointmentId)
            ->where('patient_id', auth()->id())
            ->firstOrFail();

        $this->appointmentId = $appointment->id;
    }

    #[Computed]
    public function appointment(): Appointment
    {
        return Appointment::with('payment', 'doctor.user')->findOrFail($this->appointmentId);
    }

    public function checkPaymentStatus(): void
    {
        // Polling method — stops when paid or failed
        $appointment = $this->appointment;

        if (in_array($appointment->payment_status, ['paid', 'refunded'])) {
            $this->dispatch('payment-resolved');
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.payment-status');
    }
}
