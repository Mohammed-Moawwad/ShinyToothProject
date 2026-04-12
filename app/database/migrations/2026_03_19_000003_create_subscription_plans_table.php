<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('doctor_subscriptions')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->foreignId('created_by_dentist_id')->constrained('dentists')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
