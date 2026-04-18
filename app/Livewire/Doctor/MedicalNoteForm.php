<?php

namespace App\Livewire\Doctor;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Medical Notes')]
#[Layout('layouts.doctor')]
class MedicalNoteForm extends Component
{
    #[Locked]
    public int $appointmentId;

    #[Validate('required|string|max:500')]
    public string $chiefComplaint = '';

    #[Validate('required|string|max:1000')]
    public string $diagnosis = '';

    #[Validate('nullable|string|max:1000')]
    public string $prescription = '';

    #[Validate('nullable|string|max:500')]
    public string $labRequests = '';

    #[Validate('nullable|string')]
    public string $notes = '';

    #[Validate('nullable|date|after:today')]
    public string $followUpDate = '';

    public function mount(int $appointment): void
    {
        $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();
        $appointment = Appointment::where('id', $appointment)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        $this->appointmentId = $appointment->id;

        $record = $appointment->medicalRecord;
        if ($record) {
            $this->chiefComplaint = $record->chief_complaint ?? '';
            $this->diagnosis = $record->diagnosis ?? '';
            $this->prescription = $record->prescription ?? '';
            $this->labRequests = $record->lab_requests ?? '';
            $this->notes = $record->notes ?? '';
            $this->followUpDate = $record->follow_up_date?->format('Y-m-d') ?? '';
        }
    }

    public function save(): void
    {
        $this->validate();

        $appointment = Appointment::findOrFail($this->appointmentId);

        MedicalRecord::updateOrCreate(
            ['appointment_id' => $this->appointmentId],
            [
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'chief_complaint' => $this->chiefComplaint,
                'diagnosis' => $this->diagnosis,
                'prescription' => $this->prescription,
                'lab_requests' => $this->labRequests,
                'notes' => $this->notes,
                'follow_up_date' => $this->followUpDate ?: null,
            ]
        );

        $this->dispatch('medical-record-saved');
        $this->dispatch('notify', message: 'Medical notes saved successfully!');
    }

    public function render(): View
    {
        return view('livewire.doctor.medical-note-form');
    }
}
