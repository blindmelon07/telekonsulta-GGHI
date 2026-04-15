<?php

namespace App\Services;

use App\Events\AppointmentBooked;
use App\Events\AppointmentCancelled;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentService
{
    public function book(
        int $patientId,
        int $doctorId,
        string $slot,
        string $type,
        string $reason
    ): Appointment {
        $doctor = Doctor::findOrFail($doctorId);
        $scheduledAt = Carbon::parse($slot)->timezone(config('app.timezone'));
        $fee = $type === 'teleconsultation' ? $doctor->teleconsultation_fee : $doctor->consultation_fee;

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'scheduled_at' => $scheduledAt,
            'end_time' => $scheduledAt->copy()->addMinutes(30),
            'type' => $type,
            'status' => 'pending',
            'reason' => $reason,
            'payment_status' => 'unpaid',
            'amount' => $fee,
        ]);

        AppointmentBooked::dispatch($appointment);

        return $appointment;
    }

    public function confirm(Appointment $appointment): void
    {
        $appointment->update(['status' => 'confirmed']);
    }

    public function cancel(Appointment $appointment, string $reason = ''): void
    {
        $appointment->update([
            'status' => 'cancelled',
            'notes' => $reason ?: $appointment->notes,
        ]);

        AppointmentCancelled::dispatch($appointment);
    }

    public function complete(Appointment $appointment): void
    {
        $appointment->update(['status' => 'completed']);
    }

    public function markNoShow(Appointment $appointment): void
    {
        $appointment->update(['status' => 'no_show']);
    }

    public function markPaid(Appointment $appointment): void
    {
        $appointment->update(['payment_status' => 'paid']);

        if ($appointment->type === 'teleconsultation') {
            $this->confirm($appointment);
        }
    }
}
