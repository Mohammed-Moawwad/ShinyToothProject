<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->unique()->constrained('doctor_subscriptions')->cascadeOnDelete();
            $table->foreignId('dentist_id')->constrained('dentists')->cascadeOnDelete();
            $table->decimal('plan_total', 10, 2);
            $table->decimal('bonus_amount', 10, 2);
            $table->unsignedTinyInteger('rating');
            $table->boolean('is_paid')->default(true); // auto-paid on creation
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_bonuses');
    }
};
