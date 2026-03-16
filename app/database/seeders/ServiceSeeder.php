<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('services')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $services = [
            // ═══════════════════════════════════════════════════════════
            // 1. PREVENTIVE DENTISTRY (7 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'General Examination', 'description' => 'Routine check-ups and diagnostic evaluations', 'price' => 50.00, 'duration_minutes' => 30, 'category' => 'Preventive Dentistry'],
            ['name' => 'Professional Cleanings', 'description' => 'Removal of plaque and tartar (prophylaxis)', 'price' => 75.00, 'duration_minutes' => 45, 'category' => 'Preventive Dentistry', 'is_special_offer' => true],
            ['name' => 'Digital X-Rays & 3D Imaging', 'description' => 'Detailed scanning for hidden issues', 'price' => 80.00, 'duration_minutes' => 20, 'category' => 'Preventive Dentistry'],
            ['name' => 'Dental Sealants', 'description' => 'Protective coatings on chewing surfaces', 'price' => 40.00, 'duration_minutes' => 15, 'category' => 'Preventive Dentistry'],
            ['name' => 'Fluoride Treatments', 'description' => 'Strengthening enamel to prevent cavities', 'price' => 35.00, 'duration_minutes' => 10, 'category' => 'Preventive Dentistry'],
            ['name' => 'Oral Cancer Screening', 'description' => 'Early detection of oral cancer', 'price' => 60.00, 'duration_minutes' => 25, 'category' => 'Preventive Dentistry'],
            ['name' => 'Sports Mouthguards & Nightguards', 'description' => 'Custom-fitted preventive devices', 'price' => 120.00, 'duration_minutes' => 30, 'category' => 'Preventive Dentistry'],

            // ═══════════════════════════════════════════════════════════
            // 2. RESTORATIVE DENTISTRY (7 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'Dental Fillings', 'description' => 'Tooth-colored composite resins for cavities', 'price' => 100.00, 'duration_minutes' => 30, 'category' => 'Restorative Dentistry'],
            ['name' => 'Inlays & Onlays', 'description' => 'Conservative restorations between fillings and crowns', 'price' => 350.00, 'duration_minutes' => 60, 'category' => 'Restorative Dentistry'],
            ['name' => 'Crowns & Bridges', 'description' => 'Caps for damaged teeth and artificial teeth', 'price' => 600.00, 'duration_minutes' => 90, 'category' => 'Restorative Dentistry'],
            ['name' => 'Dentures', 'description' => 'Full or partial removable sets for missing teeth', 'price' => 800.00, 'duration_minutes' => 120, 'category' => 'Restorative Dentistry'],
            ['name' => 'Root Canal Therapy (Endodontics)', 'description' => 'Treatment for infected tooth pulp', 'price' => 400.00, 'duration_minutes' => 90, 'category' => 'Restorative Dentistry'],
            ['name' => 'Dental Implants', 'description' => 'Permanent surgical replacements for missing teeth', 'price' => 1800.00, 'duration_minutes' => 120, 'category' => 'Restorative Dentistry'],
            ['name' => 'Simple Tooth Extraction', 'description' => 'Basic extraction of loose or non-surgical teeth', 'price' => 120.00, 'duration_minutes' => 30, 'category' => 'Restorative Dentistry'],

            // ═══════════════════════════════════════════════════════════
            // 3. COSMETIC DENTISTRY (5 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'Teeth Whitening', 'description' => 'Professional bleaching (in-office or take-home kits)', 'price' => 200.00, 'duration_minutes' => 45, 'category' => 'Cosmetic Dentistry', 'is_special_offer' => true],
            ['name' => 'Porcelain Veneers', 'description' => 'Thin custom-made shells for front teeth', 'price' => 1000.00, 'duration_minutes' => 90, 'category' => 'Cosmetic Dentistry'],
            ['name' => 'Dental Bonding', 'description' => 'Tooth-colored resin application for repairs', 'price' => 250.00, 'duration_minutes' => 45, 'category' => 'Cosmetic Dentistry'],
            ['name' => 'Gum Contouring', 'description' => 'Reshaping the gumline for aesthetic purposes', 'price' => 350.00, 'duration_minutes' => 60, 'category' => 'Cosmetic Dentistry'],
            ['name' => 'Smile Makeovers', 'description' => 'Comprehensive customized cosmetic procedures', 'price' => 2500.00, 'duration_minutes' => 180, 'category' => 'Cosmetic Dentistry', 'is_special_offer' => true],

            // ═══════════════════════════════════════════════════════════
            // 4. ORTHODONTICS (3 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'Traditional Braces', 'description' => 'Metal or ceramic brackets and wires', 'price' => 3500.00, 'duration_minutes' => 90, 'category' => 'Orthodontics'],
            ['name' => 'Clear Aligners', 'description' => 'Invisible aligner trays (like Invisalign)', 'price' => 4000.00, 'duration_minutes' => 60, 'category' => 'Orthodontics', 'is_special_offer' => true],
            ['name' => 'Retainers', 'description' => 'Custom devices to maintain teeth position', 'price' => 300.00, 'duration_minutes' => 30, 'category' => 'Orthodontics'],

            // ═══════════════════════════════════════════════════════════
            // 5. PERIODONTICS (Gum Disease Treatment) (3 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'Scaling and Root Planing', 'description' => 'Deep cleaning to treat gum disease', 'price' => 300.00, 'duration_minutes' => 60, 'category' => 'Periodontics'],
            ['name' => 'Gum Grafting', 'description' => 'Surgery to treat receding gums', 'price' => 1200.00, 'duration_minutes' => 90, 'category' => 'Periodontics'],
            ['name' => 'Periodontal Maintenance', 'description' => 'Ongoing specialized cleanings', 'price' => 150.00, 'duration_minutes' => 45, 'category' => 'Periodontics'],

            // ═══════════════════════════════════════════════════════════
            // 6. SPECIALTY & ADDITIONAL SERVICES (4 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'Oral Surgery', 'description' => 'Complex extractions and bone grafting', 'price' => 800.00, 'duration_minutes' => 90, 'category' => 'Specialty & Additional Services'],
            ['name' => 'Emergency Dentistry', 'description' => 'Urgent care for severe toothaches and trauma', 'price' => 250.00, 'duration_minutes' => 45, 'category' => 'Specialty & Additional Services'],
            ['name' => 'TMJ Therapy', 'description' => 'Treatments for jaw joint pain and disorders', 'price' => 200.00, 'duration_minutes' => 60, 'category' => 'Specialty & Additional Services'],
            ['name' => 'Sleep Apnea/Snoring Appliances', 'description' => 'Custom mouthguards for airway opening', 'price' => 450.00, 'duration_minutes' => 45, 'category' => 'Specialty & Additional Services'],

            // ═══════════════════════════════════════════════════════════
            // 7. PEDIATRIC DENTISTRY (4 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'Children\'s Dental Exams', 'description' => 'Age-appropriate check-ups', 'price' => 40.00, 'duration_minutes' => 30, 'category' => 'Pediatric Dentistry'],
            ['name' => 'Children\'s Cleanings', 'description' => 'Gentle preventive cleaning for kids', 'price' => 50.00, 'duration_minutes' => 30, 'category' => 'Pediatric Dentistry'],
            ['name' => 'Fluoride Treatments for Children', 'description' => 'Topical fluoride for growing teeth', 'price' => 30.00, 'duration_minutes' => 10, 'category' => 'Pediatric Dentistry'],
            ['name' => 'Sealants for Kids', 'description' => 'Protective coatings on permanent molars', 'price' => 35.00, 'duration_minutes' => 15, 'category' => 'Pediatric Dentistry'],

            // ═══════════════════════════════════════════════════════════
            // 8. CONSULTATION SERVICES (2 services)
            // ═══════════════════════════════════════════════════════════
            ['name' => 'Free 5-Minute Consultation', 'description' => 'Quick assessment with dentist', 'price' => 0.00, 'duration_minutes' => 5, 'category' => 'Consultation'],
            ['name' => '1-Hour Comprehensive Consultation', 'description' => 'In-depth examination and treatment plan', 'price' => 100.00, 'duration_minutes' => 60, 'category' => 'Consultation'],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert(array_merge($service, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
