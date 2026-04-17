<?php

use App\Livewire\Public\BookAppointment;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\SlotGeneratorService;
use Carbon\Carbon;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create a doctor with a schedule on Monday (day_of_week=1)
    $this->doctor = Doctor::factory()->create([
        'is_available_online' => true,
        'is_active' => true,
    ]);

    $this->doctor->user->assignRole('doctor');

    Schedule::factory()->forDay(1)->create([
        'doctor_id' => $this->doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '17:00:00',
        'slot_duration_minutes' => 30,
        'appointment_type' => 'both',
        'is_active' => true,
    ]);
});

test('slot generator returns slots for a valid schedule day', function () {
    $monday = Carbon::now()->next(Carbon::MONDAY)->toDateString();

    $slots = app(SlotGeneratorService::class)->getAvailableSlots(
        $this->doctor->id,
        $monday,
        'in_person'
    );

    expect($slots)->not->toBeEmpty()
        ->and($slots[0])->toBe('09:00');
});

test('slot generator returns empty array when no schedule exists for that day', function () {
    // No Saturday schedule created
    $saturday = Carbon::now()->next(Carbon::SATURDAY)->toDateString();

    $slots = app(SlotGeneratorService::class)->getAvailableSlots(
        $this->doctor->id,
        $saturday,
        'in_person'
    );

    expect($slots)->toBeEmpty();
});

test('book appointment page loads for a valid doctor', function () {
    $response = $this->get("/doctors/{$this->doctor->id}/book");

    $response->assertStatus(200);
});

test('book appointment component renders step 1 with appointment type selection', function () {
    Livewire::test(BookAppointment::class, ['doctor' => $this->doctor->id])
        ->assertSet('step', 1)
        ->assertSee('In-Person')
        ->assertSee('Teleconsultation');
});

test('book appointment advances to step 2 after selecting appointment type', function () {
    Livewire::test(BookAppointment::class, ['doctor' => $this->doctor->id])
        ->set('appointmentType', 'in_person')
        ->call('nextStep')
        ->assertSet('step', 2);
});

test('book appointment shows slots on step 2 for a scheduled day', function () {
    $monday = Carbon::now()->next(Carbon::MONDAY)->toDateString();

    Livewire::test(BookAppointment::class, ['doctor' => $this->doctor->id])
        ->set('appointmentType', 'in_person')
        ->call('nextStep')
        ->set('selectedDate', $monday)
        ->assertSeeHtml('09:00');
});

test('changing the selected date clears the selected slot', function () {
    $monday = Carbon::now()->next(Carbon::MONDAY)->toDateString();
    $nextMonday = Carbon::now()->next(Carbon::MONDAY)->addWeek()->toDateString();

    // Add a schedule for the second Monday too (same doctor, same day_of_week=1, already created)
    Livewire::test(BookAppointment::class, ['doctor' => $this->doctor->id])
        ->set('appointmentType', 'in_person')
        ->call('nextStep')
        ->set('selectedDate', $monday)
        ->set('selectedSlot', '09:00')
        ->assertSet('selectedSlot', '09:00')
        ->set('selectedDate', $nextMonday)
        ->assertSet('selectedSlot', '');
});
