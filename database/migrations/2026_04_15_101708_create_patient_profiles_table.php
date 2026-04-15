<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('philhealth_number', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_profiles');
    }
};
