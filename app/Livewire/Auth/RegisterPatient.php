<?php

namespace App\Livewire\Auth;

use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Create Account')]
#[Layout('layouts.guest')]
class RegisterPatient extends Component
{
    public int $step = 1;

    // Step 1 — Basic info
    #[Validate('')]
    public string $name = '';

    #[Validate('')]
    public string $email = '';

    #[Validate('')]
    public string $password = '';

    #[Validate('')]
    public string $passwordConfirmation = '';

    #[Validate('')]
    public string $phone = '';

    // Step 2 — Personal info
    #[Validate('')]
    public string $dateOfBirth = '';

    #[Validate('')]
    public string $gender = '';

    #[Validate('')]
    public string $address = '';

    // Step 3 — Medical profile
    public string $bloodType = '';

    public string $allergies = '';

    public string $medicalHistory = '';

    public function stepOneRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }

    public function stepTwoRules(): array
    {
        return [
            'dateOfBirth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }

    public function nextStep(): void
    {
        match ($this->step) {
            1 => $this->validate($this->stepOneRules()),
            2 => $this->validate($this->stepTwoRules()),
            default => null,
        };

        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function register(): void
    {
        $this->validate($this->stepOneRules());
        $this->validate($this->stepTwoRules());

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'date_of_birth' => $this->dateOfBirth,
            'gender' => $this->gender,
            'address' => $this->address,
            'is_active' => true,
        ]);

        $user->assignRole('patient');

        PatientProfile::create([
            'user_id' => $user->id,
            'blood_type' => $this->bloodType ?: null,
            'allergies' => $this->allergies ?: null,
            'medical_history' => $this->medicalHistory ?: null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirectRoute('dashboard');
    }

    public function render(): View
    {
        return view('livewire.auth.register-patient');
    }
}
