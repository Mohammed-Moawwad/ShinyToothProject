<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::all();

        // ── Best dentist per category (by specialization match) ──────────
        // Only dentists whose specialization matches the service category
        // are considered. Scored: (avg_rating × 2) + (experience_years × 0.3)
        $bestByCategory = DB::table('dentists')
            ->join('dentist_specialization', 'dentists.id', '=', 'dentist_specialization.dentist_id')
            ->join('specializations', 'dentist_specialization.specialization_id', '=', 'specializations.id')
            ->leftJoin('doctor_ratings', 'doctor_ratings.dentist_id', '=', 'dentists.id')
            ->where('dentists.status', 'active')
            ->select(
                'specializations.name as category',
                'dentists.id as dentist_id',
                'dentists.name as dentist_name',
                'dentists.image as dentist_image',
                'dentists.experience_years',
                DB::raw('ROUND(COALESCE(AVG(doctor_ratings.rating), 0), 1) as avg_rating'),
                DB::raw('COUNT(DISTINCT doctor_ratings.id) as rating_count'),
                DB::raw('(COALESCE(AVG(doctor_ratings.rating), 0) * 2 + COALESCE(dentists.experience_years, 0) * 0.3) as score')
            )
            ->groupBy(
                'specializations.name',
                'dentists.id',
                'dentists.name',
                'dentists.image',
                'dentists.experience_years'
            )
            ->get()
            ->groupBy('category')
            ->map(fn($group) => $group->sortByDesc('score')->first());

        // ── Attach best_dentist to each service by category ──────────────
        $services = $services->map(function ($service) use ($bestByCategory) {
            $service->best_dentist = $bestByCategory->get($service->category);
            return $service;
        });

        return view('Services.Services', compact('services'));
    }

    public function show(int $id)
    {
        $service = Service::findOrFail($id);

        // Specialization name matches service category exactly
        // (Specializations updated to match the 8 service categories)
        $targetSpecialization = $service->category;

        // Dentists busy RIGHT NOW: scheduled appointment today within ±60 min of current time
        $now = Carbon::now();
        $busyDentistIds = DB::table('appointments')
            ->where('appointment_date', $now->toDateString())
            ->where('status', 'scheduled')
            ->whereRaw('appointment_time BETWEEN ? AND ?', [
                $now->copy()->subHour()->format('H:i:s'),
                $now->copy()->addHour()->format('H:i:s'),
            ])
            ->pluck('dentist_id')
            ->toArray();

        // Active dentists with matching specialization, not busy, ranked by score
        $dentists = DB::table('dentists')
            ->join('dentist_specialization', 'dentists.id', '=', 'dentist_specialization.dentist_id')
            ->join('specializations', 'dentist_specialization.specialization_id', '=', 'specializations.id')
            ->leftJoin('doctor_ratings', 'doctor_ratings.dentist_id', '=', 'dentists.id')
            ->where('dentists.status', 'active')
            ->where('specializations.name', $targetSpecialization)
            ->whereNotIn('dentists.id', $busyDentistIds)
            ->select(
                'dentists.id',
                'dentists.name',
                'dentists.image',
                'dentists.experience_years',
                'dentists.university',
                DB::raw('GROUP_CONCAT(DISTINCT specializations.name ORDER BY specializations.name SEPARATOR ", ") as specializations_list'),
                DB::raw('ROUND(COALESCE(AVG(doctor_ratings.rating), 0), 1) as avg_rating'),
                DB::raw('COUNT(DISTINCT doctor_ratings.id) as rating_count')
            )
            ->groupBy(
                'dentists.id',
                'dentists.name',
                'dentists.image',
                'dentists.experience_years',
                'dentists.university'
            )
            ->orderByDesc(DB::raw('COALESCE(AVG(doctor_ratings.rating), 0) * 2 + COALESCE(dentists.experience_years, 0) * 0.3'))
            ->get();

        return view('Services.ServiceDetail', compact('service', 'dentists'));
    }
}
