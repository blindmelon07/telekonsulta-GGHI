<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use App\Models\AppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPatientCancellation implements ShouldQueue
{
    public function handle(AppointmentCancelled $event): void
    {
        $appointment = $event->appointment->load('doctor.user');

        AppNotification::create([
            'user_id' => $appointment->patient_id,
            'type' => 'appointment_cancelled',
            'title' => 'Appointment Cancelled',
            'message' => "Your appointment with Dr. {$appointment->doctor->user->name} on "
                .$appointment->scheduled_at->format('M d, Y \a\t h:i A').' has been cancelled.',
            'data' => ['appointment_id' => $appointment->id],
        ]);
    }
}
