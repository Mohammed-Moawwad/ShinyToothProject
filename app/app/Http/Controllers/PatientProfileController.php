<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientProfileController extends Controller
{
    public function show()
    {
        $patient = Auth::user();
        return view('patient.profile', compact('patient'));
    }

    public function update(Request $request)
    {
        $patient = Auth::user();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female',
            'blood_type'    => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'place_of_birth'=> 'nullable|string|max:255',
            'nationality'   => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:500',
        ]);

        $patient->update($validated);

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully.');
    }
}
