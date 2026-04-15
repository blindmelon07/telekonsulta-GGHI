<?php

namespace App\Listeners;

use App\Events\AppointmentConfirmed;
use App\Events\PaymentConfirmed;
use App\Models\AppNotification;
use App\Services\AppointmentService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmAppointmentListener implements ShouldQueue
{
    public function __construct(private readonly AppointmentService $appointmentService) {}

    public function handle(PaymentConfirmed $event): void
    {
        $appointment = $event->appointment;

        if ($appointment->type === 'in_person' && $appointment->status === 'pending') {
            $this->appointmentService->confirm($appointment);
            AppointmentConfirmed::dispatch($appointment);
        }
    }
}
