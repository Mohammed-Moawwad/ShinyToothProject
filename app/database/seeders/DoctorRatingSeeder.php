<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorRatingSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('doctor_ratings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // patient_id, dentist_id, appointment_id, rating, review
        $ratings = [
            [1, 1, 1,  5, 'Dr. Abdulaziz was excellent! Very professional and gentle.'],
            [2, 2, 2,  5, 'Amazing results on my whitening. Highly recommend Dr. Hana!'],
            [3, 3, 3,  4, 'Dr. Tariq was skilled but the wait was a bit long.'],
            [4, 1, 4,  5, 'Quick and painless filling. Great experience overall.'],
            [5, 4, 5,  4, 'Dr. Reem was great with kids. My child felt comfortable.'],
            [6, 5, 6,  5, 'Perfect crown placement. Dr. Faisal is very thorough.'],
            [7, 3, 7,  3, 'Extraction was painful but Dr. Tariq was professional.'],
            [8, 2, 8,  5, 'Dr. Hana is the best orthodontist. Very happy with braces!'],
            [1, 5, 9,  5, 'Implant process was smooth. Dr. Faisal explained everything.'],
            [2, 1, 10, 4, 'Good gum treatment. Significant improvement after the session.'],
        ];

        foreach ($ratings as [$patientId, $dentistId, $appointmentId, $rating, $review]) {
            DB::table('doctor_ratings')->insert([
                'patient_id'     => $patientId,
                'dentist_id'     => $dentistId,
                'appointment_id' => $appointmentId,
                'rating'         => $rating,
                'review'         => $review,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
