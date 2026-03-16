<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreventiveDentistryImagesSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            'General Examination'                => 'images/services/general-examination.jpg',
            'Professional Cleanings'             => 'images/services/professional-cleanings.webp',
            'Digital X-Rays & 3D Imaging'        => 'images/services/digital-xrays-3d-imaging.jpg',
            'Dental Sealants'                    => 'images/services/dental-sealants.webp',
            'Fluoride Treatments'                => 'images/services/fluoride-treatments.jpg',
            'Oral Cancer Screening'              => 'images/services/oral-cancer-screening.webp',
            'Sports Mouthguards & Nightguards'   => 'images/services/sports-mouthguards-nightguards.webp',
        ];

        foreach ($serviceImages as $serviceName => $imagePath) {
            DB::table('services')
                ->where('name', $serviceName)
                ->where('category', 'Preventive Dentistry')
                ->update(['image' => $imagePath]);
        }
    }
}
