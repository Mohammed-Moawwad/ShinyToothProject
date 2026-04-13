<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_written_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dentist_id')->constrained('dentists')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('content');
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_written_reports');
    }
};
