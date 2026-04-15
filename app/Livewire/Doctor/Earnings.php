<?php

namespace App\Livewire\Doctor;

use App\Models\Doctor;
use App\Models\Payment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('My Earnings')]
#[Layout('layouts.doctor')]
class Earnings extends Component
{
    #[Url]
    public string $period = 'month';

    #[Computed]
    public function doctor(): Doctor
    {
        return Doctor::where('user_id', auth()->id())->firstOrFail();
    }

    #[Computed]
    public function summary(): array
    {
        $doctorId = $this->doctor->id;

        $query = Payment::whereHas('appointment', fn ($q) => $q->where('doctor_id', $doctorId))->paid();

        $filtered = match ($this->period) {
            'week' => $query->clone()->whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->clone()->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year),
            'year' => $query->clone()->whereYear('paid_at', now()->year),
            default => $query->clone()->whereMonth('paid_at', now()->month),
        };

        $total = $filtered->sum('amount');

        return [
            'total' => $total / 100,
            'count' => $filtered->count(),
            'average' => $filtered->count() > 0 ? ($total / $filtered->count()) / 100 : 0,
            'payments' => $filtered->with('appointment.patient')->orderByDesc('paid_at')->take(50)->get(),
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.doctor.earnings');
    }
}
