<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'phone', 'profile_photo', 'date_of_birth', 'gender', 'address', 'is_active'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function patientProfile(): HasOne
    {
        return $this->hasOne(PatientProfile::class);
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    /** @return HasMany<Appointment> */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /** @return HasMany<Payment> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'patient_id');
    }

    /** @return HasMany<AppNotification> */
    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()->whereNull('read_at')->count();
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('doctor');
    }

    public function isPatient(): bool
    {
        return $this->hasRole('patient');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
