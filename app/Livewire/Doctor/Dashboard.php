<?php

namespace App\Livewire\Doctor;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctor Dashboard')]
#[Layout('layouts.doctor')]
class Dashboard extends Component
{
    #[Computed]
    public function doctor(): Doctor
    {
        return Doctor::with('specialization')->where('user_id', auth()->id())->firstOrFail();
    }

    #[Computed]
    public function todaySchedule()
    {
        return Appointment::with('patient')
            ->where('doctor_id', $this->doctor->id)
            ->whereDate('scheduled_at', today())
            ->orderBy('scheduled_at')
            ->get();
    }

    #[Computed]
    public function stats(): array
    {
        $doctorId = $this->doctor->id;

        return [
            'today' => Appointment::where('doctor_id', $doctorId)->whereDate('scheduled_at', today())->count(),
            'upcoming' => Appointment::where('doctor_id', $doctorId)->upcoming()->count(),
            'completed_month' => Appointment::where('doctor_id', $doctorId)->completed()
                ->whereMonth('scheduled_at', now()->month)->count(),
            'earnings_month' => Payment::whereHas('appointment', fn ($q) => $q->where('doctor_id', $doctorId))
                ->paid()->whereMonth('paid_at', now()->month)->sum('amount') / 100,
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.doctor.dashboard');
    }
}
