<?php

namespace App\Livewire\Public;

use App\Models\Doctor;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctor Profile')]
#[Layout('layouts.guest')]
class DoctorProfile extends Component
{
    #[Locked]
    public int $doctorId;

    public function mount(int $doctor): void
    {
        $this->doctorId = $doctor;
    }

    #[Computed]
    public function doctor(): Doctor
    {
        return Doctor::with('user', 'specialization')->findOrFail($this->doctorId);
    }

    public function render(): View
    {
        return view('livewire.public.doctor-profile');
    }
}
