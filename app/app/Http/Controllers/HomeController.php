<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use App\Models\Service;

class HomeController extends Controller
{
    /**
     * Show the homepage with active dentists sorted by experience
     */
    public function index()
    {
        $dentists = Dentist::where('status', 'active')
            ->with('specializations')
            ->orderBy('experience_years', 'desc')
            ->limit(3)
            ->get();

        $services = Service::limit(4)->get();

        return view('Homepage.Homepage', [
            'dentists' => $dentists,
            'services' => $services,
        ]);
    }
}
