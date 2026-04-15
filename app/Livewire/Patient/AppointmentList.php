<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('My Appointments')]
#[Layout('layouts.patient')]
class AppointmentList extends Component
{
    use WithPagination;

    #[Url]
    public string $tab = 'upcoming';

    #[Url]
    public int $page = 1;

    #[Url]
    public string $type = '';

    public function updatedTab(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function appointments()
    {
        $query = Appointment::with('doctor.user', 'doctor.specialization')
            ->where('patient_id', auth()->id())
            ->when($this->type, fn ($q) => $q->where('type', $this->type));

        return match ($this->tab) {
            'upcoming' => $query->upcoming()->orderBy('scheduled_at')->paginate(10),
            'past' => $query->completed()->orderByDesc('scheduled_at')->paginate(10),
            'cancelled' => $query->cancelled()->orderByDesc('scheduled_at')->paginate(10),
            default => $query->upcoming()->orderBy('scheduled_at')->paginate(10),
        };
    }

    public function render(): View
    {
        return view('livewire.patient.appointment-list');
    }
}
