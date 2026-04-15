<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Reports')]
#[Layout('layouts.admin')]
class Reports extends Component
{
    #[Computed]
    public function chartData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            $revenue = Payment::paid()
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', $month)
                ->sum('amount') / 100;

            $appointments = Appointment::whereYear('scheduled_at', now()->year)
                ->whereMonth('scheduled_at', $month)
                ->count();

            return [
                'month' => now()->month($month)->format('M'),
                'revenue' => $revenue,
                'appointments' => $appointments,
            ];
        });

        return [
            'labels' => $months->pluck('month')->toArray(),
            'revenue' => $months->pluck('revenue')->toArray(),
            'appointments' => $months->pluck('appointments')->toArray(),
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.reports');
    }
}
