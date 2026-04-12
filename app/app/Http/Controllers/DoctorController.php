<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use App\Models\DoctorRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Show listing of all active dentists.
     * GET /doctors
     */
    public function index()
    {
        $dentists = Dentist::where('status', 'active')
            ->with('specializations')
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->get();

        return view('doctors.index', compact('dentists'));
    }

    /**
     * Show a single dentist profile.
     * GET /doctors/{id}
     */
    public function show($id)
    {
        $dentist = Dentist::where('status', 'active')
            ->with('specializations')
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->findOrFail($id);

        $reviews = DoctorRating::where('dentist_id', $id)
            ->with('patient')
            ->latest()
            ->limit(10)
            ->get();

        return view('doctors.show', compact('dentist', 'reviews'));
    }
}
