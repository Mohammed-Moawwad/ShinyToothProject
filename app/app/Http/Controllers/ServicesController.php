<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::all();

        // ── Best dentist per service ──────────────────────────────────────
        // Find dentists who have handled each service via appointments,
        // then score them: (avg_rating × 2) + (experience_years × 0.3)
        $dentistsByService = DB::table('appointments')
            ->join('dentists', 'appointments.dentist_id', '=', 'dentists.id')
            ->leftJoin('doctor_ratings', 'doctor_ratings.dentist_id', '=', 'dentists.id')
            ->where('dentists.status', 'active')
            ->select(
                'appointments.service_id',
                'dentists.id as dentist_id',
                'dentists.name as dentist_name',
                'dentists.image as dentist_image',
                'dentists.experience_years',
                DB::raw('ROUND(COALESCE(AVG(doctor_ratings.rating), 0), 1) as avg_rating'),
                DB::raw('COUNT(DISTINCT doctor_ratings.id) as rating_count'),
                DB::raw('(COALESCE(AVG(doctor_ratings.rating), 0) * 2 + COALESCE(dentists.experience_years, 0) * 0.3) as score')
            )
            ->groupBy(
                'appointments.service_id',
                'dentists.id',
                'dentists.name',
                'dentists.image',
                'dentists.experience_years'
            )
            ->get()
            ->groupBy('service_id')
            ->map(fn($group) => $group->sortByDesc('score')->first());

        // ── Fallback: best overall dentist (for services with no appointment history) ──
        $topDentistOverall = DB::table('dentists')
            ->leftJoin('doctor_ratings', 'doctor_ratings.dentist_id', '=', 'dentists.id')
            ->where('dentists.status', 'active')
            ->select(
                'dentists.id as dentist_id',
                'dentists.name as dentist_name',
                'dentists.image as dentist_image',
                'dentists.experience_years',
                DB::raw('ROUND(COALESCE(AVG(doctor_ratings.rating), 0), 1) as avg_rating'),
                DB::raw('COUNT(DISTINCT doctor_ratings.id) as rating_count')
            )
            ->groupBy('dentists.id', 'dentists.name', 'dentists.image', 'dentists.experience_years')
            ->orderByDesc(DB::raw('COALESCE(AVG(doctor_ratings.rating), 0) * 2 + COALESCE(dentists.experience_years, 0) * 0.3'))
            ->first();

        // ── Attach best_dentist to each service ──────────────────────────
        $services = $services->map(function ($service) use ($dentistsByService, $topDentistOverall) {
            $service->best_dentist = $dentistsByService->get($service->id) ?? $topDentistOverall;
            return $service;
        });

        return view('Services.Services', compact('services'));
    }
}
