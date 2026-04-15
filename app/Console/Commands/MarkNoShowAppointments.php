<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('appointments:mark-no-show')]
#[Description('Auto-mark past confirmed appointments as no-show')]
class MarkNoShowAppointments extends Command
{
    public function handle(AppointmentService $appointmentService): int
    {
        $appointments = Appointment::where('status', 'confirmed')
            ->where('scheduled_at', '<', now()->subHour())
            ->get();

        foreach ($appointments as $appointment) {
            $appointmentService->markNoShow($appointment);
        }

        $this->info("Marked {$appointments->count()} appointment(s) as no-show.");

        return self::SUCCESS;
    }
}
