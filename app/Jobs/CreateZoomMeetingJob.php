<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\ZoomService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateZoomMeetingJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly int $appointmentId) {}

    public function handle(ZoomService $zoomService): void
    {
        $appointment = Appointment::findOrFail($this->appointmentId);

        if ($appointment->zoom_meeting_id) {
            return;
        }

        $zoomService->createMeetingForAppointment($appointment);
    }
}
