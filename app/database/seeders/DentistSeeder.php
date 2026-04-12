<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DentistSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('dentist_specialization')->truncate();
        DB::table('dentists')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $dentists = [
            ['name' => 'Dr. Abdulaziz Al-Mutairi', 'email' => 'abdulaziz@shinytooth.com', 'phone' => '0501010101', 'salary' => 18000.00, 'hire_date' => '2018-03-01', 'status' => 'active',    'place_of_birth' => 'Riyadh',  'nationality' => 'Saudi', 'experience_years' => 10, 'university' => 'King Saud University'],
            ['name' => 'Dr. Hana Al-Amri',         'email' => 'hana@shinytooth.com',       'phone' => '0502020202', 'salary' => 15000.00, 'hire_date' => '2020-07-15', 'status' => 'active',    'place_of_birth' => 'Jeddah',  'nationality' => 'Saudi', 'experience_years' => 7,  'university' => 'King Abdulaziz University'],
            ['name' => 'Dr. Tariq Al-Subaie',      'email' => 'tariq@shinytooth.com',      'phone' => '0503030303', 'salary' => 20000.00, 'hire_date' => '2015-01-10', 'status' => 'active',    'place_of_birth' => 'Dammam',  'nationality' => 'Saudi', 'experience_years' => 14, 'university' => 'King Faisal University'],
            ['name' => 'Dr. Reem Al-Bishi',        'email' => 'reem@shinytooth.com',       'phone' => '0504040404', 'salary' => 14000.00, 'hire_date' => '2021-09-01', 'status' => 'on_leave', 'place_of_birth' => 'Riyadh',  'nationality' => 'Saudi', 'experience_years' => 5,  'university' => 'Riyadh Colleges of Dentistry'],
            ['name' => 'Dr. Faisal Al-Osaimi',     'email' => 'faisal@shinytooth.com',     'phone' => '0505050505', 'salary' => 17000.00, 'hire_date' => '2019-04-20', 'status' => 'active',    'place_of_birth' => 'Mecca',   'nationality' => 'Saudi', 'experience_years' => 9,  'university' => 'Umm Al-Qura University'],
        ];

        foreach ($dentists as $dentist) {
            DB::table('dentists')->insert(array_merge($dentist, [
                'password'   => Hash::make('dentist123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Assign ONE specialization to each dentist (matches our 8 service categories)
        $assignments = [
            1 => 1,  // Abdulaziz → Preventive Dentistry
            2 => 4,  // Hana → Orthodontics
            3 => 2,  // Tariq → Restorative Dentistry
            4 => 7,  // Reem → Pediatric Dentistry
            5 => 3,  // Faisal → Cosmetic Dentistry
        ];

        foreach ($assignments as $dentistId => $specId) {
            DB::table('dentist_specialization')->insert([
                'dentist_id'        => $dentistId,
                'specialization_id' => $specId,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
