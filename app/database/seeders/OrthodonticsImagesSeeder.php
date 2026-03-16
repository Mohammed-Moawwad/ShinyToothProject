<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrthodonticsImagesSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            'Traditional Braces'        => 'images/services/traditional-braces.webp',
            'Clear Aligners'            => 'images/services/clear-aligners.webp',
            'Retainers'                 => 'images/services/retainers.webp',
        ];

        foreach ($serviceImages as $serviceName => $imagePath) {
            DB::table('services')
                ->where('name', $serviceName)
                ->where('category', 'Orthodontics')
                ->update(['image' => $imagePath]);
        }
    }
}
