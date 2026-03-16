<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodonticsImagesSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            'Scaling and Root Planing'      => 'images/services/scaling-root-planing.webp',
            'Gum Grafting'                  => 'images/services/gum-grafting.webp',
            'Periodontal Maintenance'       => 'images/services/periodontal-maintenance.webp',
        ];

        foreach ($serviceImages as $serviceName => $imagePath) {
            DB::table('services')
                ->where('name', $serviceName)
                ->where('category', 'Periodontics')
                ->update(['image' => $imagePath]);
        }
    }
}
