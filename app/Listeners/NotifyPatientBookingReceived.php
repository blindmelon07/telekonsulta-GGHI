<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Models\AppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPatientBookingReceived implements ShouldQueue
{
    public function handle(AppointmentBooked $event): void
    {
        $appointment = $event->appointment->load('doctor.user');

        AppNotification::create([
            'user_id' => $appointment->patient_id,
            'type' => 'booking_received',
            'title' => 'Appointment Booking Received',
            'message' => "Your appointment with Dr. {$appointment->doctor->user->name} on "
                .$appointment->scheduled_at->format('M d, Y \a\t h:i A').' is pending payment.',
            'data' => ['appointment_id' => $appointment->id],
        ]);
    }
}
