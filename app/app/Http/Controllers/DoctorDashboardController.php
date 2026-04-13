<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        return view('doctor.dashboard');
    }

    public function appointments()
    {
        return view('doctor.appointments');
    }

    public function subscriptions()
    {
        return view('doctor.subscriptions');
    }

    public function planDesigner($id)
    {
        return view('doctor.plan-designer', ['id' => $id]);
    }

    public function reports()
    {
        return view('doctor.reports');
    }

    public function bonuses()
    {
        return view('doctor.bonuses');
    }

    public function profile()
    {
        return view('doctor.profile');
    }
}
