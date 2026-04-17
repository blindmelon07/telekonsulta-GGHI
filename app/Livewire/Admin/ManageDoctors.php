<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
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

    // Create doctor form
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public string $licenseNumber = '';

    public int|string $specializationId = '';

    public int|string $experienceYears = '';

    public string $bio = '';

    public string $clinicAddress = '';

    public int|string $consultationFee = '';

    public int|string $teleconsultationFee = '';

    public bool $isAvailableOnline = false;

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

    #[Computed]
    public function specializations()
    {
        return Specialization::orderBy('name')->get();
    }

    public function createDoctor(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
            'licenseNumber' => ['required', 'string', 'max:100', 'unique:doctors,license_number'],
            'specializationId' => ['required', 'exists:specializations,id'],
            'experienceYears' => ['required', 'integer', 'min:0', 'max:60'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'clinicAddress' => ['nullable', 'string', 'max:500'],
            'consultationFee' => ['required', 'numeric', 'min:0'],
            'teleconsultationFee' => ['required_if:isAvailableOnline,true', 'nullable', 'numeric', 'min:0'],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $user->assignRole('doctor');

        Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $this->specializationId,
            'license_number' => $this->licenseNumber,
            'experience_years' => $this->experienceYears,
            'bio' => $this->bio ?: null,
            'clinic_address' => $this->clinicAddress ?: null,
            'consultation_fee' => (int) round($this->consultationFee * 100),
            'teleconsultation_fee' => $this->isAvailableOnline ? (int) round($this->teleconsultationFee * 100) : 0,
            'is_available_online' => $this->isAvailableOnline,
            'is_active' => true,
        ]);

        $this->reset([
            'name', 'email', 'phone', 'password', 'passwordConfirmation',
            'licenseNumber', 'specializationId', 'experienceYears', 'bio',
            'clinicAddress', 'consultationFee', 'teleconsultationFee', 'isAvailableOnline',
        ]);

        unset($this->doctors);

        $this->modal('create-doctor')->close();

        $this->dispatch('doctor-created');
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

    public function render(): View
    {
        return view('livewire.admin.manage-doctors');
    }
}
