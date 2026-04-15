<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Teleconsultation Room')]
#[Layout('layouts.patient')]
class TeleconsultationRoom extends Component
{
    #[Locked]
    public int $appointmentId;

    public ?string $timeUntilMeeting = null;

    public bool $canJoin = false;

    public function mount(int $appointmentId): void
    {
        $appointment = Appointment::where('id', $appointmentId)
            ->where('patient_id', auth()->id())
            ->where('type', 'teleconsultation')
            ->firstOrFail();

        $this->appointmentId = $appointment->id;
        $this->refreshCountdown();
    }

    #[Computed]
    public function appointment(): Appointment
    {
        return Appointment::with('doctor.user')->findOrFail($this->appointmentId);
    }

    public function refreshCountdown(): void
    {
        $appointment = $this->appointment;
        $now = now()->timezone(config('app.timezone'));
        $scheduledAt = $appointment->scheduled_at->timezone(config('app.timezone'));

        $diff = $now->diffInMinutes($scheduledAt, false);

        if ($diff <= 5 && $diff >= -60) {
            $this->canJoin = true;
            $this->timeUntilMeeting = 'Now';
        } elseif ($diff > 0) {
            $this->canJoin = false;
            $this->timeUntilMeeting = "In {$diff} minutes";
        } else {
            $this->canJoin = false;
            $this->timeUntilMeeting = 'Meeting has ended';
        }
    }

    #[On('appointment-confirmed')]
    public function onAppointmentConfirmed(): void
    {
        unset($this->appointment);
        $this->refreshCountdown();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.teleconsultation-room');
    }
}
