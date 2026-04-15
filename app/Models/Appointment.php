<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'scheduled_at',
        'end_time',
        'type',
        'status',
        'reason',
        'notes',
        'payment_status',
        'amount',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'zoom_password',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'end_time' => 'datetime',
            'amount' => 'integer',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getAmountInPesosAttribute(): float
    {
        return $this->amount / 100;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isTeleconsultation(): bool
    {
        return $this->type === 'teleconsultation';
    }

    public function canJoin(): bool
    {
        if (! $this->isTeleconsultation() || ! $this->isConfirmed()) {
            return false;
        }

        return now()->diffInMinutes($this->scheduled_at, false) >= -5;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', '>', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
