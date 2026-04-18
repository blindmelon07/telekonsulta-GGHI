<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\AppointmentService;
use App\Services\PaymentService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('My Appointments')]
#[Layout('layouts.patient')]
class AppointmentList extends Component
{
    use WithPagination;

    #[Url]
    public string $tab = 'upcoming';

    #[Url]
    public int $page = 1;

    #[Url]
    public string $type = '';

    public function mount(PaymentService $paymentService): void
    {
        // Sync any pending QRPh/card payment intents that may have succeeded
        // without a webhook (e.g. dev environment or webhook delay).
        Payment::where('patient_id', auth()->id())
            ->where('status', 'pending')
            ->whereNotNull('paymongo_payment_intent_id')
            ->with('appointment')
            ->get()
            ->each(function (Payment $payment) use ($paymentService) {
                $intent = $paymentService->retrievePaymentIntent($payment->paymongo_payment_intent_id);
                if (($intent['attributes']['status'] ?? null) === 'succeeded' && ! $payment->isPaid()) {
                    $payment->update(['status' => 'paid', 'paid_at' => now()]);
                    $payment->appointment->update(['payment_status' => 'paid']);
                    app(AppointmentService::class)->markPaid($payment->appointment);
                }
            });
    }

    public function updatedTab(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function appointments()
    {
        $query = Appointment::with('doctor.user', 'doctor.specialization')
            ->where('patient_id', auth()->id())
            ->when($this->type, fn ($q) => $q->where('type', $this->type));

        return match ($this->tab) {
            'upcoming' => $query->upcoming()->orderBy('scheduled_at')->paginate(10),
            'past' => $query->completed()->orderByDesc('scheduled_at')->paginate(10),
            'cancelled' => $query->cancelled()->orderByDesc('scheduled_at')->paginate(10),
            default => $query->upcoming()->orderBy('scheduled_at')->paginate(10),
        };
    }

    public function render(): View
    {
        return view('livewire.patient.appointment-list');
    }
}
