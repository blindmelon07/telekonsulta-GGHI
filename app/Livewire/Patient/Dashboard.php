<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Patient Dashboard')]
#[Layout('layouts.patient')]
class Dashboard extends Component
{
    #[Computed]
    public function upcomingAppointments()
    {
        return Appointment::with('doctor.user', 'doctor.specialization')
            ->where('patient_id', auth()->id())
            ->upcoming()
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function stats(): array
    {
        $userId = auth()->id();

        return [
            'total' => Appointment::where('patient_id', $userId)->count(),
            'upcoming' => Appointment::where('patient_id', $userId)->upcoming()->count(),
            'completed' => Appointment::where('patient_id', $userId)->completed()->count(),
            'cancelled' => Appointment::where('patient_id', $userId)->cancelled()->count(),
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.dashboard');
    }
}
