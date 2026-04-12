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
            ['name' => 'Preventive Dentistry',   'description' => 'Routine dental care including cleanings, exams, X-rays, and preventive treatments'],
            ['name' => 'Restorative Dentistry',  'description' => 'Tooth restoration with fillings, crowns, bridges, implants, and dentures'],
            ['name' => 'Cosmetic Dentistry',     'description' => 'Aesthetic procedures including whitening, veneers, bonding, and smile makeovers'],
            ['name' => 'Orthodontics',           'description' => 'Teeth and jaw alignment correction using braces, aligners, and retainers'],
            ['name' => 'Periodontics',           'description' => 'Treatment and management of gum disease and supporting tooth structures'],
            ['name' => 'Specialty Services',     'description' => 'Advanced oral surgery, implantation, TMJ therapy, and emergency dentistry'],
            ['name' => 'Pediatric Dentistry',    'description' => 'Specialized dental care for children and adolescents'],
            ['name' => 'Consultation',           'description' => 'Initial consultations and treatment planning for complex cases'],
        ];

        foreach ($specializations as $spec) {
            DB::table('specializations')->insert(array_merge($spec, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
