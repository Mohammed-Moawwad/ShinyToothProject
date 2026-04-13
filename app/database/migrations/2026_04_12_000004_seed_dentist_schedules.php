<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $dentistIds = DB::table('dentists')->pluck('id');
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        $now  = now();

        foreach ($dentistIds as $id) {
            foreach ($days as $day) {
                DB::table('dentist_schedules')->insertOrIgnore([
                    'dentist_id'   => $id,
                    'day_of_week'  => $day,
                    'start_time'   => '09:00:00',
                    'end_time'     => '17:00:00',
                    'is_available' => 1,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('dentist_schedules')->delete();
    }
};
