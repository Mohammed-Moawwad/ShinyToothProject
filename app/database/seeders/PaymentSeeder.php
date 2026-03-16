<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('payments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Only seed payments for completed appointments (IDs 1-10)
        // appointment_id, patient_id, amount, date, method, status
        $payments = [
            [1,  1, 60.00,   '2026-01-05', 'cash',        'completed'],
            [2,  2, 150.00,  '2026-01-10', 'credit_card', 'completed'],
            [3,  3, 350.00,  '2026-01-15', 'debit_card',  'completed'],
            [4,  4, 80.00,   '2026-01-20', 'cash',        'completed'],
            [5,  5, 40.00,   '2026-01-22', 'cash',        'completed'],
            [6,  6, 500.00,  '2026-02-01', 'credit_card', 'completed'],
            [7,  7, 100.00,  '2026-02-05', 'cash',        'completed'],
            [8,  8, 2500.00, '2026-02-10', 'credit_card', 'completed'],
            [9,  1, 1200.00, '2026-02-18', 'debit_card',  'completed'],
            [10, 2, 200.00,  '2026-02-25', 'cash',        'completed'],
        ];

        foreach ($payments as [$appointmentId, $patientId, $amount, $date, $method, $status]) {
            DB::table('payments')->insert([
                'appointment_id' => $appointmentId,
                'patient_id'     => $patientId,
                'amount'         => $amount,
                'payment_date'   => $date,
                'payment_method' => $method,
                'status'         => $status,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
