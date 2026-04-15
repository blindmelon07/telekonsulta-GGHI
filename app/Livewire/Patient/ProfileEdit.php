<?php

namespace App\Livewire\Patient;

use App\Models\PatientProfile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Edit Profile')]
#[Layout('layouts.patient')]
class ProfileEdit extends Component
{
    public string $activeTab = 'personal';

    // Personal info
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|date')]
    public string $dateOfBirth = '';

    #[Validate('nullable|in:male,female,other')]
    public string $gender = '';

    #[Validate('nullable|string|max:500')]
    public string $address = '';

    // Medical info
    #[Validate('nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-')]
    public string $bloodType = '';

    #[Validate('nullable|string|max:500')]
    public string $allergies = '';

    #[Validate('nullable|string')]
    public string $medicalHistory = '';

    #[Validate('nullable|string|max:255')]
    public string $emergencyContactName = '';

    #[Validate('nullable|string|max:20')]
    public string $emergencyContactPhone = '';

    #[Validate('nullable|string|max:50')]
    public string $philhealthNumber = '';

    public function mount(): void
    {
        $user = auth()->user();
        $profile = $user->patientProfile;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->dateOfBirth = $user->date_of_birth?->format('Y-m-d') ?? '';
        $this->gender = $user->gender ?? '';
        $this->address = $user->address ?? '';

        if ($profile) {
            $this->bloodType = $profile->blood_type ?? '';
            $this->allergies = $profile->allergies ?? '';
            $this->medicalHistory = $profile->medical_history ?? '';
            $this->emergencyContactName = $profile->emergency_contact_name ?? '';
            $this->emergencyContactPhone = $profile->emergency_contact_phone ?? '';
            $this->philhealthNumber = $profile->philhealth_number ?? '';
        }
    }

    public function savePersonal(): void
    {
        $this->validateOnly(['name', 'email', 'phone', 'dateOfBirth', 'gender', 'address']);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth ?: null,
            'gender' => $this->gender ?: null,
            'address' => $this->address,
        ]);

        $this->dispatch('notify', message: 'Personal information updated!', type: 'success');
    }

    public function saveMedical(): void
    {
        $this->validateOnly(['bloodType', 'allergies', 'medicalHistory', 'emergencyContactName', 'emergencyContactPhone', 'philhealthNumber']);

        PatientProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'blood_type' => $this->bloodType ?: null,
                'allergies' => $this->allergies,
                'medical_history' => $this->medicalHistory,
                'emergency_contact_name' => $this->emergencyContactName,
                'emergency_contact_phone' => $this->emergencyContactPhone,
                'philhealth_number' => $this->philhealthNumber,
            ]
        );

        $this->dispatch('notify', message: 'Medical information updated!', type: 'success');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.profile-edit');
    }
}
