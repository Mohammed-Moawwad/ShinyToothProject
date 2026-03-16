<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('reports')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $reports = [
            [
                'title'        => 'January 2026 Monthly Report',
                'type'         => 'monthly',
                'start_date'   => '2026-01-01',
                'end_date'     => '2026-01-31',
                'generated_by' => 'Admin',
                'data'         => json_encode(['total_appointments' => 5, 'total_revenue' => 680.00, 'new_patients' => 5]),
            ],
            [
                'title'        => 'February 2026 Monthly Report',
                'type'         => 'monthly',
                'start_date'   => '2026-02-01',
                'end_date'     => '2026-02-28',
                'generated_by' => 'Admin',
                'data'         => json_encode(['total_appointments' => 5, 'total_revenue' => 4550.00, 'new_patients' => 3]),
            ],
            [
                'title'        => 'Q1 2026 Quarterly Report',
                'type'         => 'custom',
                'start_date'   => '2026-01-01',
                'end_date'     => '2026-03-31',
                'generated_by' => 'Admin',
                'data'         => json_encode(['total_appointments' => 15, 'total_revenue' => 5230.00, 'new_patients' => 8]),
            ],
        ];

        foreach ($reports as $report) {
            DB::table('reports')->insert(array_merge($report, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
