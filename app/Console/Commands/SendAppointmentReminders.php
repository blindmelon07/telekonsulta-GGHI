<?php

namespace App\Console\Commands;

use App\Jobs\SendAppointmentReminderJob;
use App\Models\Appointment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('appointments:send-reminders')]
#[Description('Send reminders for appointments starting in ~15 minutes')]
class SendAppointmentReminders extends Command
{
    public function handle(): int
    {
        $appointments = Appointment::whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('scheduled_at', [now()->addMinutes(10), now()->addMinutes(20)])
            ->get();

        foreach ($appointments as $appointment) {
            SendAppointmentReminderJob::dispatch($appointment->id);
        }

        $this->info("Dispatched reminders for {$appointments->count()} appointment(s).");

        return self::SUCCESS;
    }
}
