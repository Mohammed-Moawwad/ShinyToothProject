<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('dentist_id')->constrained('dentists')->cascadeOnDelete();

            $table->enum('status', [
                'pending',    // patient sent request, waiting for doctor
                'active',     // doctor accepted
                'idle',       // doctor paused the subscription
                'completed',  // doctor marked plan as completed
                'cancelled',  // patient cancelled (admin approved)
                'switched',   // patient switched to another doctor (admin approved)
                'rejected',   // doctor rejected the request
                'removed',    // admin removed the patient
            ])->default('pending');

            $table->enum('admin_action_status', [
                'none',              // no pending admin action
                'pending_switch',    // patient requested a switch, awaiting admin
                'pending_cancel',    // patient requested cancellation, awaiting admin
                'pending_removal',   // doctor requested removal, awaiting admin
            ])->default('none');

            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Reasons
            $table->text('rejection_reason')->nullable();
            $table->text('patient_switch_reason')->nullable();
            $table->text('patient_cancel_reason')->nullable();
            $table->text('admin_removal_reason')->nullable();
            $table->text('doctor_removal_reason')->nullable();

            // Switch target
            $table->foreignId('switch_to_dentist_id')
                  ->nullable()
                  ->constrained('dentists')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_subscriptions');
    }
};
