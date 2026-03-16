<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Order matters — dependencies must be seeded first
        $this->call([
            SpecializationSeeder::class,  // No dependencies
            ServiceSeeder::class,         // No dependencies
            PatientSeeder::class,         // No dependencies
            DentistSeeder::class,         // Depends on Specialization (also seeds dentist_specialization)
            AppointmentSeeder::class,     // Depends on Patient, Dentist, Service
            PaymentSeeder::class,         // Depends on Appointment, Patient
            DoctorRatingSeeder::class,    // Depends on Patient, Dentist, Appointment
            ReportSeeder::class,          // No dependencies
            FinancialSeeder::class,       // No dependencies
        ]);
    }
}
