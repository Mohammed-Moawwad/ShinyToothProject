<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('patients')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $patients = [
            ['name' => 'Ahmed Al-Rashidi',  'email' => 'ahmed.rashidi@email.com',  'phone' => '0501234567', 'date_of_birth' => '1990-05-15', 'gender' => 'male',   'address' => 'Riyadh, Al Olaya',     'blood_type' => 'O+',  'place_of_birth' => 'Riyadh',  'nationality' => 'Saudi'],
            ['name' => 'Sara Al-Otaibi',    'email' => 'sara.otaibi@email.com',    'phone' => '0509876543', 'date_of_birth' => '1995-08-22', 'gender' => 'female', 'address' => 'Jeddah, Al Hamra',     'blood_type' => 'A+',  'place_of_birth' => 'Jeddah',  'nationality' => 'Saudi'],
            ['name' => 'Mohammed Al-Qahtani','email' => 'moh.qahtani@email.com',   'phone' => '0551112233', 'date_of_birth' => '1985-03-10', 'gender' => 'male',   'address' => 'Dammam, Al Faisaliya', 'blood_type' => 'B+',  'place_of_birth' => 'Dammam',  'nationality' => 'Saudi'],
            ['name' => 'Fatima Al-Zahrani', 'email' => 'fatima.zahrani@email.com', 'phone' => '0564445566', 'date_of_birth' => '1998-11-30', 'gender' => 'female', 'address' => 'Mecca, Aziziyah',      'blood_type' => 'AB+', 'place_of_birth' => 'Mecca',   'nationality' => 'Saudi'],
            ['name' => 'Khalid Al-Dosari',  'email' => 'khalid.dosari@email.com',  'phone' => '0577778899', 'date_of_birth' => '1978-07-04', 'gender' => 'male',   'address' => 'Madinah, Al Haram',    'blood_type' => 'O-',  'place_of_birth' => 'Madinah', 'nationality' => 'Saudi'],
            ['name' => 'Noura Al-Shehri',   'email' => 'noura.shehri@email.com',   'phone' => '0533334455', 'date_of_birth' => '2000-01-18', 'gender' => 'female', 'address' => 'Riyadh, Al Malaz',     'blood_type' => 'A-',  'place_of_birth' => 'Riyadh',  'nationality' => 'Saudi'],
            ['name' => 'Omar Al-Ghamdi',    'email' => 'omar.ghamdi@email.com',    'phone' => '0522223344', 'date_of_birth' => '1993-09-25', 'gender' => 'male',   'address' => 'Taif, Al Shafa',       'blood_type' => 'B-',  'place_of_birth' => 'Taif',    'nationality' => 'Saudi'],
            ['name' => 'Lina Al-Harbi',     'email' => 'lina.harbi@email.com',     'phone' => '0544556677', 'date_of_birth' => '1988-12-05', 'gender' => 'female', 'address' => 'Khobar, Thuqbah',      'blood_type' => 'AB-', 'place_of_birth' => 'Khobar',  'nationality' => 'Saudi'],
        ];

        foreach ($patients as $patient) {
            DB::table('patients')->insert(array_merge($patient, [
                'password'   => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
