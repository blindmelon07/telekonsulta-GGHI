<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete();
            $table->dateTime('scheduled_at');
            $table->dateTime('end_time')->nullable();
            $table->enum('type', ['in_person', 'teleconsultation'])->default('in_person');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->unsignedInteger('amount')->default(0)->comment('In centavos');
            $table->string('zoom_meeting_id')->nullable();
            $table->text('zoom_join_url')->nullable();
            $table->text('zoom_start_url')->nullable();
            $table->string('zoom_password')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'scheduled_at']);
            $table->index(['patient_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
