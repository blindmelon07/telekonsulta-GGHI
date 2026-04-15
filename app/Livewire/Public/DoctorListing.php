<?php

namespace App\Livewire\Public;

use App\Models\Doctor;
use App\Models\Specialization;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Find a Doctor')]
#[Layout('layouts.guest')]
class DoctorListing extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $specialization = '';

    #[Url]
    public string $type = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSpecialization(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function doctors()
    {
        return Doctor::with('user', 'specialization')
            ->active()
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->specialization, fn ($q) => $q->where('specialization_id', $this->specialization))
            ->when($this->type === 'teleconsultation', fn ($q) => $q->online())
            ->paginate(12);
    }

    #[Computed]
    public function specializations()
    {
        return Specialization::orderBy('name')->get();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.public.doctor-listing');
    }
}
