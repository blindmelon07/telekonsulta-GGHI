<?php

namespace App\Livewire\Doctor;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Services\AppointmentService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Meeting Room')]
#[Layout('layouts.doctor')]
class MeetingRoom extends Component
{
    #[Locked]
    public int $appointmentId;

    #[Validate('nullable|string|max:1000')]
    public string $quickNote = '';

    public function mount(int $appointmentId): void
    {
        $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();
        Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->where('type', 'teleconsultation')
            ->firstOrFail();

        $this->appointmentId = $appointmentId;
    }

    #[Computed]
    public function appointment(): Appointment
    {
        return Appointment::with('patient', 'medicalRecord')->findOrFail($this->appointmentId);
    }

    public function markComplete(): void
    {
        $appointment = $this->appointment;
        app(AppointmentService::class)->complete($appointment);

        if ($this->quickNote) {
            $appointment->update(['notes' => $this->quickNote]);
        }

        $this->dispatch('appointment-completed', appointmentId: $this->appointmentId);
        $this->redirectRoute('doctor.appointments');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.doctor.meeting-room');
    }
}
