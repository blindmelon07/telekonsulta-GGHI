<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialization_id')->constrained()->restrictOnDelete();
            $table->text('bio')->nullable();
            $table->unsignedInteger('consultation_fee')->default(0)->comment('In centavos');
            $table->unsignedInteger('teleconsultation_fee')->default(0)->comment('In centavos');
            $table->unsignedSmallInteger('experience_years')->default(0);
            $table->string('license_number', 50)->nullable();
            $table->text('clinic_address')->nullable();
            $table->boolean('is_available_online')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
