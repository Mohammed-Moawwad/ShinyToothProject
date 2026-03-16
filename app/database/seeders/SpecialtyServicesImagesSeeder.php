<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtyServicesImagesSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            'Oral Surgery'                  => 'images/services/oral-surgery.webp',
            'Emergency Dentistry'           => 'images/services/emergency-dentistry.webp',
            'TMJ Therapy'                   => 'images/services/tmj-therapy.webp',
            'Sleep Apnea/Snoring Appliances' => 'images/services/sleep-apnea-snoring.webp',
        ];

        foreach ($serviceImages as $serviceName => $imagePath) {
            DB::table('services')
                ->where('name', $serviceName)
                ->where('category', 'Specialty & Additional Services')
                ->update(['image' => $imagePath]);
        }
    }
}
