<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manage Doctors')]
#[Layout('layouts.admin')]
class ManageDoctors extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function doctors()
    {
        return Doctor::with('user', 'specialization')
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")))
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    public function toggleActive(int $doctorId): void
    {
        $doctor = Doctor::findOrFail($doctorId);
        $doctor->update(['is_active' => ! $doctor->is_active]);
        $doctor->user->update(['is_active' => ! $doctor->user->is_active]);
        unset($this->doctors);
    }

    public function delete(int $doctorId): void
    {
        Doctor::findOrFail($doctorId)->delete();
        unset($this->doctors);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.manage-doctors');
    }
}
