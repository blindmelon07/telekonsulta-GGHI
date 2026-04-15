<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Schedule;
use Carbon\Carbon;

class SlotGeneratorService
{
    /**
     * Get available time slots for a doctor on a given date and type.
     *
     * @return array<int, string>
     */
    public function getAvailableSlots(int $doctorId, string $date, string $type): array
    {
        $carbon = Carbon::parse($date)->timezone(config('app.timezone'));
        $dayOfWeek = (int) $carbon->dayOfWeek;

        $scheduleQuery = Schedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true);

        if ($type !== 'both') {
            $scheduleQuery->where(function ($q) use ($type) {
                $q->where('appointment_type', $type)
                    ->orWhere('appointment_type', 'both');
            });
        }

        $schedule = $scheduleQuery->first();

        if (! $schedule) {
            return [];
        }

        $slots = $this->generateSlots(
            $carbon->toDateString(),
            $schedule->start_time,
            $schedule->end_time,
            $schedule->slot_duration_minutes
        );

        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $carbon->toDateString())
            ->whereNotIn('status', ['cancelled'])
            ->pluck('scheduled_at')
            ->map(fn ($dt) => Carbon::parse($dt)->timezone(config('app.timezone'))->format('H:i'))
            ->toArray();

        $twoHoursFromNow = now()->timezone(config('app.timezone'))->addHours(2);

        return array_values(array_filter($slots, function ($slot) use ($carbon, $bookedSlots, $twoHoursFromNow) {
            $slotTime = Carbon::parse($carbon->toDateString().' '.$slot)->timezone(config('app.timezone'));

            if ($slotTime->lte($twoHoursFromNow)) {
                return false;
            }

            return ! in_array($slot, $bookedSlots);
        }));
    }

    /** @return array<int, string> */
    private function generateSlots(string $date, string $startTime, string $endTime, int $durationMinutes): array
    {
        $slots = [];
        $current = Carbon::parse($date.' '.$startTime);
        $end = Carbon::parse($date.' '.$endTime);

        while ($current->copy()->addMinutes($durationMinutes)->lte($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($durationMinutes);
        }

        return $slots;
    }
}
