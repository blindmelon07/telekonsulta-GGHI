<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Appointment Oversight')]
#[Layout('layouts.admin')]
class AppointmentOversight extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    #[Url]
    public string $date = '';

    #[Url]
    public string $type = '';

    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedDate(): void { $this->resetPage(); }
    public function updatedType(): void { $this->resetPage(); }

    #[Computed]
    public function appointments()
    {
        return Appointment::with('patient', 'doctor.user')
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->date, fn ($q) => $q->whereDate('scheduled_at', $this->date))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->orderByDesc('scheduled_at')
            ->paginate(20);
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $appointments = Appointment::with('patient', 'doctor.user')
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->date, fn ($q) => $q->whereDate('scheduled_at', $this->date))
            ->get();

        return response()->streamDownload(function () use ($appointments) {
            $csv = fopen('php://output', 'w');
            fputcsv($csv, ['ID', 'Patient', 'Doctor', 'Date', 'Type', 'Status', 'Payment', 'Amount']);

            foreach ($appointments as $a) {
                fputcsv($csv, [
                    $a->id,
                    $a->patient->name,
                    $a->doctor->user->name,
                    $a->scheduled_at->format('Y-m-d H:i'),
                    $a->type,
                    $a->status,
                    $a->payment_status,
                    number_format($a->amount / 100, 2),
                ]);
            }

            fclose($csv);
        }, 'appointments-'.now()->format('Y-m-d').'.csv');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.appointment-oversight');
    }
}
