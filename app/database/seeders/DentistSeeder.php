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

        // 24 dentists: 70% men (17), 30% women (7)
        // 60% Saudi (14), 40% other: Jordan (3), India (3), Egypt (2), Sudan (2)
        // Specializations: 1=Preventive, 2=Restorative, 3=Cosmetic, 4=Orthodontics
        //                   5=Periodontics, 6=Specialty Services, 7=Pediatric, 8=Consultation
        $dentists = [
            // --- Preventive Dentistry (spec 1) ---  2M 1F | 2 Saudi 1 Jordan
            ['name' => 'Dr. Abdulaziz Al-Mutairi', 'email' => 'abdulaziz@shinytooth.com',  'phone' => '0501010101', 'salary' => 18000.00, 'hire_date' => '2018-03-01', 'status' => 'active',    'place_of_birth' => 'Riyadh',    'nationality' => 'Saudi',    'experience_years' => 10, 'university' => 'King Saud University'],
            ['name' => 'Dr. Omar Al-Dosari',        'email' => 'omar@shinytooth.com',       'phone' => '0501020304', 'salary' => 16500.00, 'hire_date' => '2019-06-01', 'status' => 'active',    'place_of_birth' => 'Medina',    'nationality' => 'Saudi',    'experience_years' => 8,  'university' => 'Taibah University'],
            ['name' => 'Dr. Hana Al-Amri',          'email' => 'hana@shinytooth.com',       'phone' => '0502020202', 'salary' => 15000.00, 'hire_date' => '2020-07-15', 'status' => 'active',    'place_of_birth' => 'Jeddah',    'nationality' => 'Saudi',    'experience_years' => 7,  'university' => 'King Abdulaziz University'],

            // --- Restorative Dentistry (spec 2) ---  2M 1F | 2 Saudi 1 India
            ['name' => 'Dr. Tariq Al-Subaie',       'email' => 'tariq@shinytooth.com',      'phone' => '0503030303', 'salary' => 20000.00, 'hire_date' => '2015-01-10', 'status' => 'active',    'place_of_birth' => 'Dammam',    'nationality' => 'Saudi',    'experience_years' => 14, 'university' => 'King Faisal University'],
            ['name' => 'Dr. Khalid Al-Rashidi',     'email' => 'khalid@shinytooth.com',     'phone' => '0505060708', 'salary' => 17500.00, 'hire_date' => '2017-11-20', 'status' => 'active',    'place_of_birth' => 'Khobar',    'nationality' => 'Saudi',    'experience_years' => 11, 'university' => 'King Saud University'],
            ['name' => 'Dr. Priya Sharma',          'email' => 'priya@shinytooth.com',      'phone' => '0509080706', 'salary' => 16000.00, 'hire_date' => '2021-03-10', 'status' => 'active',    'place_of_birth' => 'Mumbai',    'nationality' => 'Indian',   'experience_years' => 6,  'university' => 'Manipal College of Dental Sciences'],

            // --- Cosmetic Dentistry (spec 3) ---  2M 1F | 2 Saudi 1 Egypt
            ['name' => 'Dr. Faisal Al-Osaimi',      'email' => 'faisal@shinytooth.com',     'phone' => '0505050505', 'salary' => 17000.00, 'hire_date' => '2019-04-20', 'status' => 'active',    'place_of_birth' => 'Mecca',     'nationality' => 'Saudi',    'experience_years' => 9,  'university' => 'Umm Al-Qura University'],
            ['name' => 'Dr. Majed Al-Zahrani',      'email' => 'majed@shinytooth.com',      'phone' => '0507080910', 'salary' => 19000.00, 'hire_date' => '2016-08-05', 'status' => 'active',    'place_of_birth' => 'Taif',      'nationality' => 'Saudi',    'experience_years' => 12, 'university' => 'King Khalid University'],
            ['name' => 'Dr. Yasmine Hassan',        'email' => 'yasmine@shinytooth.com',    'phone' => '0508091011', 'salary' => 15500.00, 'hire_date' => '2020-02-14', 'status' => 'active',    'place_of_birth' => 'Cairo',     'nationality' => 'Egyptian', 'experience_years' => 7,  'university' => 'Cairo University Faculty of Dentistry'],

            // --- Orthodontics (spec 4) ---  2M 1F | 1 Saudi 1 Jordan 1 India
            ['name' => 'Dr. Reem Al-Bishi',         'email' => 'reem@shinytooth.com',       'phone' => '0504040404', 'salary' => 14000.00, 'hire_date' => '2021-09-01', 'status' => 'active',    'place_of_birth' => 'Riyadh',    'nationality' => 'Saudi',    'experience_years' => 5,  'university' => 'Riyadh Colleges of Dentistry'],
            ['name' => 'Dr. Walid Al-Natour',       'email' => 'walid@shinytooth.com',      'phone' => '0511121314', 'salary' => 16000.00, 'hire_date' => '2018-05-22', 'status' => 'active',    'place_of_birth' => 'Amman',     'nationality' => 'Jordanian','experience_years' => 9,  'university' => 'University of Jordan'],
            ['name' => 'Dr. Rajesh Kumar',          'email' => 'rajesh@shinytooth.com',     'phone' => '0512131415', 'salary' => 15500.00, 'hire_date' => '2019-10-01', 'status' => 'active',    'place_of_birth' => 'Delhi',     'nationality' => 'Indian',   'experience_years' => 8,  'university' => 'Maulana Azad Institute of Dental Sciences'],

            // --- Periodontics (spec 5) ---  2M 1F | 1 Saudi 1 Egypt 1 Jordan
            ['name' => 'Dr. Nasser Al-Qahtani',     'email' => 'nasser@shinytooth.com',     'phone' => '0513141516', 'salary' => 19500.00, 'hire_date' => '2014-03-15', 'status' => 'active',    'place_of_birth' => 'Riyadh',    'nationality' => 'Saudi',    'experience_years' => 15, 'university' => 'King Saud University'],
            ['name' => 'Dr. Mohamed Ibrahim',       'email' => 'mohamed@shinytooth.com',    'phone' => '0514151617', 'salary' => 17000.00, 'hire_date' => '2017-07-01', 'status' => 'active',    'place_of_birth' => 'Alexandria','nationality' => 'Egyptian', 'experience_years' => 10, 'university' => 'Alexandria University'],
            ['name' => 'Dr. Lina Haddad',           'email' => 'lina@shinytooth.com',       'phone' => '0515161718', 'salary' => 15000.00, 'hire_date' => '2020-11-10', 'status' => 'active',    'place_of_birth' => 'Irbid',     'nationality' => 'Jordanian','experience_years' => 6,  'university' => 'Jordan University of Science and Technology'],

            // --- Specialty Services (spec 6) ---  2M 1F | 2 Saudi 1 Sudan
            ['name' => 'Dr. Sultan Al-Ghamdi',      'email' => 'sultan@shinytooth.com',     'phone' => '0516171819', 'salary' => 22000.00, 'hire_date' => '2013-01-20', 'status' => 'active',    'place_of_birth' => 'Jeddah',    'nationality' => 'Saudi',    'experience_years' => 16, 'university' => 'King Abdulaziz University'],
            ['name' => 'Dr. Nawaf Al-Harbi',        'email' => 'nawaf@shinytooth.com',      'phone' => '0517181920', 'salary' => 20500.00, 'hire_date' => '2015-09-05', 'status' => 'active',    'place_of_birth' => 'Dammam',    'nationality' => 'Saudi',    'experience_years' => 13, 'university' => 'King Faisal University'],
            ['name' => 'Dr. Amira Osman',           'email' => 'amira@shinytooth.com',      'phone' => '0518192021', 'salary' => 15500.00, 'hire_date' => '2021-01-15', 'status' => 'active',    'place_of_birth' => 'Khartoum', 'nationality' => 'Sudanese', 'experience_years' => 6,  'university' => 'University of Khartoum'],

            // --- Pediatric Dentistry (spec 7) ---  2M 1F | 2 Saudi 1 India
            ['name' => 'Dr. Sara Al-Shehri',        'email' => 'sara@shinytooth.com',       'phone' => '0519202122', 'salary' => 15000.00, 'hire_date' => '2020-04-01', 'status' => 'active',    'place_of_birth' => 'Riyadh',    'nationality' => 'Saudi',    'experience_years' => 6,  'university' => 'Riyadh Colleges of Dentistry'],
            ['name' => 'Dr. Ahmed Al-Mansouri',     'email' => 'ahmed@shinytooth.com',      'phone' => '0520212223', 'salary' => 17000.00, 'hire_date' => '2018-08-20', 'status' => 'active',    'place_of_birth' => 'Mecca',     'nationality' => 'Saudi',    'experience_years' => 10, 'university' => 'Umm Al-Qura University'],
            ['name' => 'Dr. Vikram Patel',          'email' => 'vikram@shinytooth.com',     'phone' => '0521222324', 'salary' => 16000.00, 'hire_date' => '2019-12-01', 'status' => 'active',    'place_of_birth' => 'Bangalore', 'nationality' => 'Indian',   'experience_years' => 8,  'university' => 'KLE Institute of Dental Sciences'],

            // --- Consultation (spec 8) ---  3M 0F | 2 Saudi 1 Jordan 1 Sudan
            ['name' => 'Dr. Turki Al-Otaibi',       'email' => 'turki@shinytooth.com',      'phone' => '0522232425', 'salary' => 21000.00, 'hire_date' => '2014-06-15', 'status' => 'active',    'place_of_birth' => 'Riyadh',    'nationality' => 'Saudi',    'experience_years' => 14, 'university' => 'King Saud University'],
            ['name' => 'Dr. Hassan Ahmed',          'email' => 'hassan@shinytooth.com',     'phone' => '0523242526', 'salary' => 16500.00, 'hire_date' => '2018-02-10', 'status' => 'active',    'place_of_birth' => 'Omdurman',  'nationality' => 'Sudanese', 'experience_years' => 9,  'university' => 'Omdurman Islamic University'],
            ['name' => 'Dr. Khaled Mansour',        'email' => 'khaled@shinytooth.com',     'phone' => '0524252627', 'salary' => 16000.00, 'hire_date' => '2019-09-15', 'status' => 'active',    'place_of_birth' => 'Zarqa',     'nationality' => 'Jordanian','experience_years' => 8,  'university' => 'University of Jordan'],
        ];

        foreach ($dentists as $dentist) {
            DB::table('dentists')->insert(array_merge($dentist, [
                'password'   => Hash::make('dentist123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 3 dentists per specialization
        $assignments = [
            // Preventive Dentistry (spec 1)
            1  => 1,
            2  => 1,
            3  => 1,
            // Restorative Dentistry (spec 2)
            4  => 2,
            5  => 2,
            6  => 2,
            // Cosmetic Dentistry (spec 3)
            7  => 3,
            8  => 3,
            9  => 3,
            // Orthodontics (spec 4)
            10 => 4,
            11 => 4,
            12 => 4,
            // Periodontics (spec 5)
            13 => 5,
            14 => 5,
            15 => 5,
            // Specialty Services (spec 6)
            16 => 6,
            17 => 6,
            18 => 6,
            // Pediatric Dentistry (spec 7)
            19 => 7,
            20 => 7,
            21 => 7,
            // Consultation (spec 8)
            22 => 8,
            23 => 8,
            24 => 8,
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
