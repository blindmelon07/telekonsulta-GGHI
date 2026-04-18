<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use Illuminate\View\View;
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

    public function mount(int $appointment): void
    {
        $appointment = Appointment::where('id', $appointment)
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

        $diffMinutes = (int) $now->diffInMinutes($scheduledAt, false);

        if ($diffMinutes <= 5 && $diffMinutes >= -60) {
            $this->canJoin = true;
            $this->timeUntilMeeting = 'Now';
        } elseif ($diffMinutes > 0) {
            $this->canJoin = false;
            if ($diffMinutes >= 60) {
                $hours = intdiv($diffMinutes, 60);
                $mins = $diffMinutes % 60;
                $this->timeUntilMeeting = $mins > 0
                    ? "In {$hours}h {$mins}m"
                    : "In {$hours} hour".($hours > 1 ? 's' : '');
            } else {
                $this->timeUntilMeeting = "In {$diffMinutes} minute".($diffMinutes > 1 ? 's' : '');
            }
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

    public function render(): View
    {
        return view('livewire.patient.teleconsultation-room');
    }
}
