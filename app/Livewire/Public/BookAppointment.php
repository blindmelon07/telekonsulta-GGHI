<?php

namespace App\Livewire\Public;

use App\Models\Doctor;
use App\Services\AppointmentService;
use App\Services\SlotGeneratorService;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Book Appointment')]
#[Layout('layouts.guest')]
class BookAppointment extends Component
{
    #[Locked]
    public int $doctorId;

    public int $step = 1;

    #[Validate('required|in:in_person,teleconsultation')]
    public string $appointmentType = 'in_person';

    #[Validate('required|date|after:today')]
    public string $selectedDate = '';

    #[Validate('required')]
    public string $selectedSlot = '';

    #[Validate('required|min:10|max:500')]
    public string $reason = '';

    public bool $isProcessing = false;

    public function mount(int $doctorId): void
    {
        $this->doctorId = $doctorId;
        $this->selectedDate = now()->addDay()->format('Y-m-d');
    }

    #[Computed]
    public function doctor(): Doctor
    {
        return Doctor::with('user', 'specialization')->findOrFail($this->doctorId);
    }

    #[Computed]
    public function slots(): array
    {
        if (! $this->selectedDate) {
            return [];
        }

        return app(SlotGeneratorService::class)->getAvailableSlots(
            $this->doctorId,
            $this->selectedDate,
            $this->appointmentType
        );
    }

    public function updatedSelectedDate(string $value): void
    {
        $this->selectedSlot = '';
        $this->resetValidation('selectedDate');
    }

    public function updatedAppointmentType(): void
    {
        $this->selectedSlot = '';
    }

    public function nextStep(): void
    {
        match ($this->step) {
            1 => $this->validateOnly(['appointmentType']),
            2 => $this->validateOnly(['selectedDate', 'selectedSlot']),
            3 => $this->validateOnly(['reason']),
        };

        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function submitBooking(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login');

            return;
        }

        $this->validate();
        $this->isProcessing = true;

        $scheduledAt = Carbon::parse($this->selectedDate.' '.$this->selectedSlot)
            ->timezone(config('app.timezone'));

        $appointment = app(AppointmentService::class)->book(
            patientId: auth()->id(),
            doctorId: $this->doctorId,
            slot: $scheduledAt->toDateTimeString(),
            type: $this->appointmentType,
            reason: $this->reason,
        );

        $this->dispatch('appointment-booked', appointmentId: $appointment->id);
        $this->redirectRoute('patient.checkout', $appointment->id);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.public.book-appointment');
    }
}
