<?php

namespace App\Livewire\Doctor;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Services\AppointmentService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Appointment Detail')]
#[Layout('layouts.doctor')]
class AppointmentDetail extends Component
{
    #[Locked]
    public int $appointmentId;

    public function mount(int $appointmentId): void
    {
        $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();
        $appointment = Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $this->appointmentId = $appointment->id;
    }

    #[Computed]
    public function appointment(): Appointment
    {
        return Appointment::with('patient.patientProfile', 'medicalRecord', 'payment')
            ->findOrFail($this->appointmentId);
    }

    public function confirm(): void
    {
        app(AppointmentService::class)->confirm($this->appointment);
        unset($this->appointment);
        $this->dispatch('appointment-updated');
    }

    public function complete(): void
    {
        app(AppointmentService::class)->complete($this->appointment);
        unset($this->appointment);
        $this->dispatch('appointment-completed');
    }

    public function cancel(): void
    {
        app(AppointmentService::class)->cancel($this->appointment);
        unset($this->appointment);
        $this->dispatch('appointment-updated');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.doctor.appointment-detail');
    }
}
