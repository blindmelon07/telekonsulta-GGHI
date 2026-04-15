<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization_id',
        'bio',
        'consultation_fee',
        'teleconsultation_fee',
        'experience_years',
        'license_number',
        'clinic_address',
        'is_available_online',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_available_online' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function getConsultationFeeInPesosAttribute(): float
    {
        return $this->consultation_fee / 100;
    }

    public function getTeleconsultationFeeInPesosAttribute(): float
    {
        return $this->teleconsultation_fee / 100;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnline($query)
    {
        return $query->where('is_available_online', true);
    }
}
