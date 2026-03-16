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
        // Map doctors to available images
        // Using available images and cycling for extra doctors
        $doctorImages = [
            'Dr. Abdulaziz Al-Mutairi' => 'images/doctors/dr-abdulaziz-al-mutairi.png',
            'Dr. Hana Al-Amri'         => 'images/doctors/dr-bajjal-kumar.png',
            'Dr. Tariq Al-Subaie'      => 'images/doctors/dr-tariq-hijazi.png',
            'Dr. Reem Al-Bishi'        => 'images/doctors/dr-abdulaziz-al-mutairi.png', // Reuse
            'Dr. Faisal Al-Osaimi'     => 'images/doctors/dr-bajjal-kumar.png', // Reuse
        ];

        foreach ($doctorImages as $name => $imagePath) {
            Dentist::where('name', $name)->update(['image' => $imagePath]);
        }
    }
}
