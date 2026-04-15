<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientProfile extends Model
{
    /** @use HasFactory<\Database\Factories\PatientProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_type',
        'allergies',
        'medical_history',
        'emergency_contact_name',
        'emergency_contact_phone',
        'philhealth_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
