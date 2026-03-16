<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PediatricDentistryImagesSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            "Children's Dental Exams"           => 'images/services/childrens-dental-exams.webp',
            "Children's Cleanings"              => 'images/services/childrens-cleanings.webp',
            'Fluoride Treatments for Children'  => 'images/services/fluoride-treatments-children.webp',
            'Sealants for Kids'                 => 'images/services/sealants-for-kids.webp',
        ];

        foreach ($serviceImages as $serviceName => $imagePath) {
            DB::table('services')
                ->where('name', $serviceName)
                ->where('category', 'Pediatric Dentistry')
                ->update(['image' => $imagePath]);
        }
    }
}
