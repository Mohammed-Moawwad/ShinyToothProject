<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CosmeticDentistryImagesSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            'Teeth Whitening'           => 'images/services/teeth-whitening.webp',
            'Porcelain Veneers'         => 'images/services/porcelain-veneers.webp',
            'Dental Bonding'            => 'images/services/dental-bonding.webp',
            'Gum Contouring'            => 'images/services/gum-contouring.webp',
            'Smile Makeovers'           => 'images/services/smile-makeovers.webp',
        ];

        foreach ($serviceImages as $serviceName => $imagePath) {
            DB::table('services')
                ->where('name', $serviceName)
                ->where('category', 'Cosmetic Dentistry')
                ->update(['image' => $imagePath]);
        }
    }
}
