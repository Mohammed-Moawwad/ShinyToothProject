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

        // Assign images to all doctors that have actual image files
        $doctorImages = [
            'Dr. Abdulaziz Al-Mutairi' => 'images/doctors/DrAbdulazizMutari.png',
            'Dr. Omar Al-Dosari'       => 'images/doctors/Dr-Omar-Al-Dosari.png',
            'Dr. Hana Al-Amri'         => 'images/doctors/Dr-Hana-Al-Amri.png',
            'Dr. Tariq Al-Subaie'      => 'images/doctors/dr-tariq-hijazi.png',
            'Dr. Khalid Al-Rashidi'    => 'images/doctors/Dr-Khalid-Al-Rashidi.png',
            'Dr. Priya Sharma'         => 'images/doctors/Dr-Priya-Sharma.png',
            'Dr. Faisal Al-Osaimi'     => 'images/doctors/Dr-Faisal-Al-Osaimi.png',
            'Dr. Majed Al-Zahrani'     => 'images/doctors/Dr-Majed-Al-Zahrani.png',
            'Dr. Yasmine Hassan'       => 'images/doctors/Dr-Yasmine-Hassan.png',
            'Dr. Reem Al-Bishi'        => 'images/doctors/Dr-Reem-Al-Bishi.png',
            'Dr. Walid Al-Natour'      => 'images/doctors/Dr-Walid-Al-Natour.png',
            'Dr. Rajesh Kumar'         => 'images/doctors/Dr-Rajesh-Kumar.png',
            'Dr. Nasser Al-Qahtani'    => 'images/doctors/Dr-Nasser-Al-Qahtani.png',
            'Dr. Mohamed Ibrahim'      => 'images/doctors/Dr-Mohamed-Ibrahim.png',
            'Dr. Lina Haddad'          => 'images/doctors/Dr-Lina-Haddad.png',
            'Dr. Sultan Al-Ghamdi'     => 'images/doctors/Dr-Sultan-Al-Ghamdi.png',
            'Dr. Nawaf Al-Harbi'       => 'images/doctors/Dr-Nawaf-Al-Harbi.png',
            'Dr. Amira Osman'          => 'images/doctors/Dr-Amira-Osman.png',
            'Dr. Sara Al-Shehri'       => 'images/doctors/Dr-Sara-Al-Shehri.png',
            'Dr. Ahmed Al-Mansouri'    => 'images/doctors/Dr-Ahmed-Al-Mansouri.png',
            'Dr. Vikram Patel'         => 'images/doctors/Dr-Vikram-Patel.png',
            'Dr. Turki Al-Otaibi'      => 'images/doctors/Dr-Turki-Al-Otaibi.png',
            'Dr. Hassan Ahmed'         => 'images/doctors/Dr-Hassan-Ahmed.png',
            'Dr. Khaled Mansour'       => 'images/doctors/Dr-KhaledMansour.png',
        ];

        foreach ($doctorImages as $name => $imagePath) {
            Dentist::where('name', $name)->update(['image' => $imagePath]);
        }
    }
}
