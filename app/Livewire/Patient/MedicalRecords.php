<?php

namespace App\Livewire\Patient;

use App\Models\MedicalRecord;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Medical Records')]
#[Layout('layouts.patient')]
class MedicalRecords extends Component
{
    #[Url]
    public string $search = '';

    #[Computed]
    public function records()
    {
        return MedicalRecord::with('appointment', 'doctor.user')
            ->where('patient_id', auth()->id())
            ->when($this->search, fn ($q) => $q->where(function ($sub) {
                $sub->where('diagnosis', 'like', "%{$this->search}%")
                    ->orWhere('chief_complaint', 'like', "%{$this->search}%");
            }))
            ->orderByDesc('created_at')
            ->get();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.patient.medical-records');
    }
}
