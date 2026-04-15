<?php

namespace App\Jobs;

use App\Models\AppNotification;
use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly int $appointmentId) {}

    public function handle(): void
    {
        $appointment = Appointment::with('doctor.user', 'patient')->findOrFail($this->appointmentId);

        if (! in_array($appointment->status, ['pending', 'confirmed'])) {
            return;
        }

        // Notify patient
        AppNotification::create([
            'user_id' => $appointment->patient_id,
            'type' => 'appointment_reminder',
            'title' => 'Appointment Reminder',
            'message' => "Reminder: Your appointment with Dr. {$appointment->doctor->user->name} is in 15 minutes.",
            'data' => ['appointment_id' => $appointment->id],
        ]);

        // Notify doctor
        AppNotification::create([
            'user_id' => $appointment->doctor->user_id,
            'type' => 'appointment_reminder',
            'title' => 'Upcoming Appointment',
            'message' => "Reminder: You have an appointment with {$appointment->patient->name} in 15 minutes.",
            'data' => ['appointment_id' => $appointment->id],
        ]);
    }
}
