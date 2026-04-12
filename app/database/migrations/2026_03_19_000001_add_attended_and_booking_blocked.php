<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('attended')->default(false)->after('status');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->boolean('booking_blocked')->default(false)->after('nationality');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('attended');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('booking_blocked');
        });
    }
};
