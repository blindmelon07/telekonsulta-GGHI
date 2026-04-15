<?php

namespace App\Listeners;

use App\Events\AppointmentConfirmed;
use App\Models\AppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPatientConfirmed implements ShouldQueue
{
    public function handle(AppointmentConfirmed $event): void
    {
        $appointment = $event->appointment->load('doctor.user');

        AppNotification::create([
            'user_id' => $appointment->patient_id,
            'type' => 'appointment_confirmed',
            'title' => 'Appointment Confirmed',
            'message' => "Your appointment with Dr. {$appointment->doctor->user->name} on "
                .$appointment->scheduled_at->format('M d, Y \a\t h:i A').' has been confirmed.',
            'data' => ['appointment_id' => $appointment->id],
        ]);
    }
}
