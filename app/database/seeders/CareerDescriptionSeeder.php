<?php

namespace Database\Seeders;

use App\Models\Dentist;
use Illuminate\Database\Seeder;

class CareerDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $descriptions = [
            "With over 12 years of experience in general dentistry, I am committed to providing compassionate and comprehensive dental care to patients of all ages. I specialize in preventive dentistry and have helped hundreds of patients achieve healthier smiles through personalized treatment plans.",
            "As a certified prosthodontist, I have dedicated my career to advanced restorative and cosmetic dentistry. My passion is transforming smiles and improving patients' quality of life through innovative dental solutions and meticulous attention to detail.",
            "My journey in pediatric dentistry began with a desire to make dental care enjoyable for children. With 8 years of specialized experience, I focus on creating a comfortable and friendly environment where young patients can develop healthy dental habits.",
            "I am an experienced orthodontist specializing in advanced teeth alignment techniques. I combine traditional and modern approaches to create beautiful, confident smiles that last a lifetime.",
            "With expertise in implant dentistry and bone grafting, I am dedicated to restoring function and aesthetics for patients with missing teeth. My commitment to continuing education ensures I offer the latest surgical techniques.",
        ];

        $dentists = Dentist::all();
        foreach ($dentists as $index => $dentist) {
            if (!$dentist->career_description) {
                $dentist->update(['career_description' => $descriptions[$index % count($descriptions)]]);
            }
        }
    }
}
