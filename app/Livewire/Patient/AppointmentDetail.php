<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Appointment Details')]
#[Layout('layouts.patient')]
class AppointmentDetail extends Component
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
        return Appointment::with('doctor.user', 'doctor.specialization', 'payment', 'medicalRecord')
            ->findOrFail($this->appointmentId);
    }

    public function cancel(): void
    {
        $appointment = $this->appointment;

        if (! in_array($appointment->status, ['pending', 'confirmed'])) {
            return;
        }

        app(AppointmentService::class)->cancel($appointment, 'Cancelled by patient');
        $this->dispatch('appointment-cancelled');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.appointment-detail');
    }
}
