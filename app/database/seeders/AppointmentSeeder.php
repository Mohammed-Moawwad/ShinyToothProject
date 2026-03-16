<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('doctor_ratings')->truncate();
        DB::table('payments')->truncate();
        DB::table('appointments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $appointments = [
            // patient, dentist, service, date,         time,    status,      notes
            [1, 1, 1,  '2026-01-05', '09:00', 'completed',  'Regular cleaning visit'],
            [2, 2, 2,  '2026-01-10', '10:30', 'completed',  'Whitening treatment session 1'],
            [3, 3, 4,  '2026-01-15', '11:00', 'completed',  'Root canal on lower molar'],
            [4, 1, 3,  '2026-01-20', '14:00', 'completed',  'Filling on upper right molar'],
            [5, 4, 9,  '2026-01-22', '09:30', 'completed',  'Routine x-ray checkup'],
            [6, 5, 6,  '2026-02-01', '13:00', 'completed',  'Crown on cracked tooth'],
            [7, 3, 5,  '2026-02-05', '10:00', 'completed',  'Wisdom tooth extraction'],
            [8, 2, 8,  '2026-02-10', '15:00', 'completed',  'Braces fitting appointment'],
            [1, 5, 7,  '2026-02-18', '11:30', 'completed',  'Implant consultation and placement'],
            [2, 1, 10, '2026-02-25', '09:00', 'completed',  'Gum scaling and deep cleaning'],
            [3, 2, 2,  '2026-03-01', '10:00', 'scheduled',  'Follow-up whitening session'],
            [4, 3, 4,  '2026-03-05', '14:30', 'scheduled',  'Second root canal session'],
            [5, 5, 6,  '2026-03-10', '11:00', 'scheduled',  'Crown fitting'],
            [6, 1, 1,  '2026-03-12', '09:00', 'cancelled',  'Patient cancelled due to travel'],
            [7, 4, 9,  '2026-03-20', '13:00', 'scheduled',  'Pediatric x-ray checkup'],
        ];

        foreach ($appointments as [$patientId, $dentistId, $serviceId, $date, $time, $status, $notes]) {
            DB::table('appointments')->insert([
                'patient_id'       => $patientId,
                'dentist_id'       => $dentistId,
                'service_id'       => $serviceId,
                'appointment_date' => $date,
                'appointment_time' => $time,
                'status'           => $status,
                'notes'            => $notes,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}
