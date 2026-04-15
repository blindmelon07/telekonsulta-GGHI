<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Payment;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Dashboard')]
#[Layout('layouts.admin')]
class Dashboard extends Component
{
    #[Computed]
    public function metrics(): array
    {
        return [
            'total_patients' => User::role('patient')->count(),
            'total_doctors' => Doctor::active()->count(),
            'appointments_today' => Appointment::whereDate('scheduled_at', today())->count(),
            'revenue_month' => Payment::paid()->whereMonth('paid_at', now()->month)->sum('amount') / 100,
            'pending_appointments' => Appointment::pending()->count(),
            'total_revenue' => Payment::paid()->sum('amount') / 100,
        ];
    }

    #[Computed]
    public function recentAppointments()
    {
        return Appointment::with('patient', 'doctor.user')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.dashboard');
    }
}
