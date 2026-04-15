<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Models\AppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyDoctorNewBooking implements ShouldQueue
{
    public function handle(AppointmentBooked $event): void
    {
        $appointment = $event->appointment->load('patient', 'doctor.user');

        AppNotification::create([
            'user_id' => $appointment->doctor->user_id,
            'type' => 'new_booking',
            'title' => 'New Appointment Booked',
            'message' => "You have a new appointment with {$appointment->patient->name} on "
                .$appointment->scheduled_at->format('M d, Y \a\t h:i A'),
            'data' => ['appointment_id' => $appointment->id],
        ]);
    }
}
