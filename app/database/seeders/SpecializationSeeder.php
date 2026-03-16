<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('specializations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $specializations = [
            ['name' => 'General Dentistry',    'description' => 'Routine dental care including cleanings, fillings, and exams'],
            ['name' => 'Orthodontics',         'description' => 'Correction of teeth and jaw alignment using braces and aligners'],
            ['name' => 'Periodontics',         'description' => 'Treatment of gum disease and supporting structures of teeth'],
            ['name' => 'Endodontics',          'description' => 'Root canal therapy and treatment of dental pulp'],
            ['name' => 'Oral Surgery',         'description' => 'Tooth extractions, implants, and jaw surgeries'],
            ['name' => 'Pediatric Dentistry',  'description' => 'Dental care for children and adolescents'],
            ['name' => 'Prosthodontics',       'description' => 'Restoration and replacement of teeth with crowns, bridges, and dentures'],
            ['name' => 'Cosmetic Dentistry',   'description' => 'Aesthetic procedures including whitening and veneers'],
        ];

        foreach ($specializations as $spec) {
            DB::table('specializations')->insert(array_merge($spec, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
