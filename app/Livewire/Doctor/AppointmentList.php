<?php

namespace App\Livewire\Doctor;

use App\Models\Appointment;
use App\Models\Doctor;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Appointments')]
#[Layout('layouts.doctor')]
class AppointmentList extends Component
{
    use WithPagination;

    #[Url]
    public string $date = '';

    #[Url]
    public string $status = '';

    public function mount(): void
    {
        $this->date = today()->format('Y-m-d');
    }

    #[Computed]
    public function doctor(): Doctor
    {
        return Doctor::where('user_id', auth()->id())->firstOrFail();
    }

    #[Computed]
    public function appointments()
    {
        return Appointment::with('patient')
            ->where('doctor_id', $this->doctor->id)
            ->when($this->date, fn ($q) => $q->whereDate('scheduled_at', $this->date))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->orderBy('scheduled_at')
            ->paginate(15);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.doctor.appointment-list');
    }
}
