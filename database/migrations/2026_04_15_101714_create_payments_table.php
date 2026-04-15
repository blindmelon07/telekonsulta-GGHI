<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->restrictOnDelete();
            $table->foreignId('patient_id')->constrained('users')->restrictOnDelete();
            $table->string('paymongo_payment_intent_id')->nullable();
            $table->string('paymongo_source_id')->nullable();
            $table->enum('method', ['gcash', 'maya', 'card', 'grab_pay'])->nullable();
            $table->unsignedInteger('amount')->comment('In centavos');
            $table->string('currency', 3)->default('PHP');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('paymongo_payment_intent_id');
            $table->index('paymongo_source_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
