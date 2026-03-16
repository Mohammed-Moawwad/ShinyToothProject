<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('financial')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // month, year, total_revenue, total_costs, profit, notes
        $records = [
            [1,  2026, 680.00,   3200.00, -2520.00, 'January - clinic launch month, low revenue'],
            [2,  2026, 4550.00,  3200.00,  1350.00, 'February - strong month with implant and braces revenue'],
            [3,  2026, 0.00,     3200.00, -3200.00, 'March - month in progress'],
            [12, 2025, 5100.00,  3000.00,  2100.00, 'December 2025 - pre-launch preparations'],
            [11, 2025, 4800.00,  3000.00,  1800.00, 'November 2025'],
        ];

        foreach ($records as [$month, $year, $revenue, $costs, $profit, $notes]) {
            DB::table('financial')->insert([
                'month'         => $month,
                'year'          => $year,
                'total_revenue' => $revenue,
                'total_costs'   => $costs,
                'profit'        => $profit,
                'notes'         => $notes,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
