<?php

use App\Http\Controllers\PayMongoWebhookController;
use App\Livewire\Admin\AppointmentOversight;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ManageDoctors;
use App\Livewire\Admin\ManagePatients;
use App\Livewire\Admin\PaymentOversight;
use App\Livewire\Admin\Reports;
use App\Livewire\Admin\SystemSettings;
use App\Livewire\Doctor\AppointmentDetail as DoctorAppointmentDetail;
use App\Livewire\Doctor\AppointmentList as DoctorAppointmentList;
use App\Livewire\Doctor\Dashboard as DoctorDashboard;
use App\Livewire\Doctor\Earnings;
use App\Livewire\Doctor\MedicalNoteForm;
use App\Livewire\Doctor\MeetingRoom;
use App\Livewire\Doctor\Notifications as DoctorNotifications;
use App\Livewire\Doctor\ScheduleManager;
use App\Livewire\Patient\AppointmentDetail as PatientAppointmentDetail;
use App\Livewire\Patient\AppointmentList as PatientAppointmentList;
use App\Livewire\Patient\Checkout;
use App\Livewire\Patient\Dashboard as PatientDashboard;
use App\Livewire\Patient\MedicalRecords;
use App\Livewire\Patient\Notifications as PatientNotifications;
use App\Livewire\Patient\PaymentHistory;
use App\Livewire\Patient\PaymentStatus;
use App\Livewire\Patient\ProfileEdit;
use App\Livewire\Patient\TeleconsultationRoom;
use App\Livewire\Auth\RegisterPatient;
use App\Livewire\Public\BookAppointment;
use App\Livewire\Public\DoctorListing;
use App\Livewire\Public\DoctorProfile;
use Illuminate\Support\Facades\Route;

// Auth routes (override Fortify's GET-only register route)
Route::get('/register', RegisterPatient::class)->middleware('guest')->name('register');

// Public routes
Route::view('/', 'welcome')->name('home');
Route::livewire('/doctors', DoctorListing::class)->name('doctors.index');
Route::livewire('/doctors/{doctor}', DoctorProfile::class)->name('doctors.show');
Route::livewire('/doctors/{doctor}/book', BookAppointment::class)->name('doctors.book');

// PayMongo webhook — exempt from CSRF via withExceptions in bootstrap/app.php
Route::post('/webhooks/paymongo', PayMongoWebhookController::class)->name('webhooks.paymongo');

// Patient routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::livewire('/dashboard', PatientDashboard::class)->name('dashboard');
    Route::livewire('/appointments', PatientAppointmentList::class)->name('appointments');
    Route::livewire('/appointments/{appointment}', PatientAppointmentDetail::class)->name('appointments.show');
    Route::livewire('/checkout/{appointment}', Checkout::class)->name('checkout');
    Route::livewire('/payment/status/{appointmentId}', PaymentStatus::class)->name('payment-status');
    Route::livewire('/payment/callback', PaymentStatus::class)->name('payment.callback');
    Route::livewire('/payments', PaymentHistory::class)->name('payments');
    Route::livewire('/medical-records', MedicalRecords::class)->name('medical-records');
    Route::livewire('/teleconsultations/{appointment}', TeleconsultationRoom::class)->name('teleconsultations');
    Route::livewire('/profile', ProfileEdit::class)->name('profile');
    Route::livewire('/notifications', PatientNotifications::class)->name('notifications');
});

// Doctor routes
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::livewire('/dashboard', DoctorDashboard::class)->name('dashboard');
    Route::livewire('/schedule', ScheduleManager::class)->name('schedule');
    Route::livewire('/appointments', DoctorAppointmentList::class)->name('appointments');
    Route::livewire('/appointments/{appointment}', DoctorAppointmentDetail::class)->name('appointments.show');
    Route::livewire('/appointments/{appointment}/notes', MedicalNoteForm::class)->name('medical-notes');
    Route::livewire('/appointments/{appointment}/meeting', MeetingRoom::class)->name('meeting');
    Route::livewire('/earnings', Earnings::class)->name('earnings');
    Route::livewire('/notifications', DoctorNotifications::class)->name('notifications');
});

// Admin routes
Route::middleware(['auth', 'role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::livewire('/doctors', ManageDoctors::class)->name('doctors');
    Route::livewire('/patients', ManagePatients::class)->name('patients');
    Route::livewire('/appointments', AppointmentOversight::class)->name('appointments');
    Route::livewire('/payments', PaymentOversight::class)->name('payments');
    Route::livewire('/reports', Reports::class)->name('reports');
    Route::livewire('/settings', SystemSettings::class)->name('settings');
});

// Authenticated redirect — send users to their role-based dashboard
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('doctor')) {
        return redirect()->route('doctor.dashboard');
    }

    return redirect()->route('patient.dashboard');
})->name('dashboard');

require __DIR__.'/settings.php';
