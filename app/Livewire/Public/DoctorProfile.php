<?php

namespace App\Livewire\Public;

use App\Models\Doctor;
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

    public function mount(int $doctorId): void
    {
        $this->doctorId = $doctorId;
    }

    #[Computed]
    public function doctor(): Doctor
    {
        return Doctor::with('user', 'specialization')->findOrFail($this->doctorId);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.public.doctor-profile');
    }
}
