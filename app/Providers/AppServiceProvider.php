<?php

namespace App\Providers;

use App\Events\AppointmentBooked;
use App\Events\AppointmentCancelled;
use App\Events\AppointmentConfirmed;
use App\Events\PaymentConfirmed;
use App\Listeners\ConfirmAppointmentListener;
use App\Listeners\NotifyDoctorNewBooking;
use App\Listeners\NotifyPatientBookingReceived;
use App\Listeners\NotifyPatientCancellation;
use App\Listeners\NotifyPatientConfirmed;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerEvents();
        $this->registerGates();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(app()->isProduction());

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)->mixedCase()->letters()->numbers()->symbols()->uncompromised()
            : null,
        );
    }

    protected function registerEvents(): void
    {
        Event::listen(AppointmentBooked::class, [NotifyDoctorNewBooking::class, 'handle']);
        Event::listen(AppointmentBooked::class, [NotifyPatientBookingReceived::class, 'handle']);
        Event::listen(PaymentConfirmed::class, [ConfirmAppointmentListener::class, 'handle']);
        Event::listen(AppointmentConfirmed::class, [NotifyPatientConfirmed::class, 'handle']);
        Event::listen(AppointmentCancelled::class, [NotifyPatientCancellation::class, 'handle']);
    }

    protected function registerGates(): void
    {
        // super_admin bypasses all permission checks
        Gate::before(function ($user, $_ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });
    }
}
