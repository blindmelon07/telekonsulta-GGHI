<?php

namespace App\Livewire\Doctor;

use App\Models\Doctor;
use App\Models\Schedule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('My Schedule')]
#[Layout('layouts.doctor')]
class ScheduleManager extends Component
{
    public ?int $editingId = null;
    public bool $showForm = false;

    #[Validate('required|integer|between:0,6')]
    public int $dayOfWeek = 1;

    #[Validate('required')]
    public string $startTime = '09:00';

    #[Validate('required|after:startTime')]
    public string $endTime = '17:00';

    #[Validate('required|integer|in:15,20,30,45,60')]
    public int $slotDurationMinutes = 30;

    #[Validate('required|in:in_person,teleconsultation,both')]
    public string $appointmentType = 'both';

    public bool $isActive = true;

    #[Computed]
    public function doctor(): Doctor
    {
        return Doctor::where('user_id', auth()->id())->firstOrFail();
    }

    #[Computed]
    public function schedules()
    {
        return Schedule::where('doctor_id', $this->doctor->id)
            ->orderBy('day_of_week')
            ->get();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'dayOfWeek', 'startTime', 'endTime', 'slotDurationMinutes', 'appointmentType', 'isActive']);
        $this->showForm = true;
    }

    public function edit(int $scheduleId): void
    {
        $schedule = Schedule::where('id', $scheduleId)
            ->where('doctor_id', $this->doctor->id)
            ->firstOrFail();

        $this->editingId = $schedule->id;
        $this->dayOfWeek = $schedule->day_of_week;
        $this->startTime = $schedule->start_time;
        $this->endTime = $schedule->end_time;
        $this->slotDurationMinutes = $schedule->slot_duration_minutes;
        $this->appointmentType = $schedule->appointment_type;
        $this->isActive = $schedule->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'doctor_id' => $this->doctor->id,
            'day_of_week' => $this->dayOfWeek,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'slot_duration_minutes' => $this->slotDurationMinutes,
            'appointment_type' => $this->appointmentType,
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            Schedule::where('id', $this->editingId)
                ->where('doctor_id', $this->doctor->id)
                ->update($data);
        } else {
            Schedule::create($data);
        }

        $this->showForm = false;
        $this->reset(['editingId']);
        unset($this->schedules);
    }

    public function delete(int $scheduleId): void
    {
        Schedule::where('id', $scheduleId)
            ->where('doctor_id', $this->doctor->id)
            ->delete();

        unset($this->schedules);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.doctor.schedule-manager');
    }
}
