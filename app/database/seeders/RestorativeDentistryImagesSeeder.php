<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestorativeDentistryImagesSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            'Dental Fillings'                    => 'images/services/dental-fillings.webp',
            'Inlays & Onlays'                    => 'images/services/inlays-onlays.webp',
            'Crowns & Bridges'                   => 'images/services/crowns-bridges.webp',
            'Dentures'                           => 'images/services/dentures.webp',
            'Root Canal Therapy (Endodontics)'   => 'images/services/root-canal-therapy.webp',
            'Dental Implants'                    => 'images/services/dental-implants.webp',
            'Simple Tooth Extraction'            => 'images/services/simple-tooth-extraction.webp',
        ];

        foreach ($serviceImages as $serviceName => $imagePath) {
            DB::table('services')
                ->where('name', $serviceName)
                ->where('category', 'Restorative Dentistry')
                ->update(['image' => $imagePath]);
        }
    }
}
