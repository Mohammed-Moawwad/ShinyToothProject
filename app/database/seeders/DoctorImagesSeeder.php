<?php

namespace Database\Seeders;

use App\Models\Dentist;
use Illuminate\Database\Seeder;

class DoctorImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, clear ALL doctor images - reset to NULL
        Dentist::query()->update(['image' => null]);

        // Then assign images ONLY to doctors that have actual image files
        $doctorImages = [
            'Dr. Abdulaziz Al-Mutairi' => 'images/doctors/dr-abdulaziz-al-mutairi.png',
            'Dr. Tariq Al-Subaie'      => 'images/doctors/dr-tariq-hijazi.png',
            // Note: Dr. Hana, Dr. Reem, Dr. Faisal do NOT have images - will show placeholder
        ];

        foreach ($doctorImages as $name => $imagePath) {
            Dentist::where('name', $name)->update(['image' => $imagePath]);
        }
    }
}
