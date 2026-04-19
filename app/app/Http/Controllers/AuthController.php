<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new patient.
     */
    public function registerPatient(Request $request)
    {
        $data = $request->validate([
            'full_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:patients,email',
            'password'       => 'required|string|min:8|confirmed',
            'phone'          => 'required|string|max:20',
            'date_of_birth'  => 'required|date',
            'gender'         => 'required|in:male,female',
            'address'        => 'nullable|string',
            'blood_type'     => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'place_of_birth' => 'required|string|max:255',
            'nationality'    => 'required|string|max:100',
        ]);

        // Map full_name to name for the Patient model
        $data['name'] = $data['full_name'];
        unset($data['full_name']);

        // Ensure address is never null (DB column is NOT NULL)
        $data['address'] = $data['address'] ?? '';

        // Password is hashed automatically by the Patient model's 'hashed' cast
        $patient = Patient::create($data);

        $token = $patient->createToken('patient-token')->plainTextToken;

        // Always create web session when session is available (web routes)
        if ($request->hasSession()) {
            Auth::guard('web')->login($patient);
            $request->session()->regenerate();
        }

        // If this is a web form submission (not API/JSON), redirect instead
        if (!$request->expectsJson() && !$request->is('api/*')) {
            return redirect('/patient/dashboard');
        }

        return response()->json([
            'patient' => $patient,
            'token'   => $token,
        ], 201);
    }

    /**
     * Login - handles both patients and dentists (unified login).
     * Tries dentist first, then patient.
     */
    public function loginPatient(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // First try to find a dentist (doctor) with this email
        $dentist = Dentist::where('email', $request->email)->first();
        
        if ($dentist && Hash::check($request->password, $dentist->password)) {
            $token = $dentist->createToken('dentist-token')->plainTextToken;
            
            return response()->json([
                'dentist'   => $dentist,
                'token'     => $token,
                'user_type' => 'dentist',
                'redirect'  => '/doctor/dashboard?dentist=' . $dentist->id,
            ]);
        }

        // Then try to find a patient with this email
        $patient = Patient::where('email', $request->email)->first();

        if (! $patient || ! Hash::check($request->password, $patient->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $patient->createToken('patient-token')->plainTextToken;

        // Establish a web session for all patients (needed for dashboard)
        Auth::guard('web')->login($patient);
        $request->session()->regenerate();

        return response()->json([
            'patient' => $patient,
            'token'   => $token,
            'user_type' => 'patient',
            'redirect' => '/patient/dashboard',
        ]);
    }

    /**
     * Logout a patient (revoke token).
     */
    public function logoutPatient(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * Register a new dentist.
     */
    public function registerDentist(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:dentists,email',
            'password'       => 'required|string|min:8|confirmed',
            'phone'          => 'nullable|string|max:20',
            'salary'         => 'nullable|numeric|min:0',
            'hire_date'      => 'nullable|date',
            'status'         => 'nullable|in:active,inactive,on_leave',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality'    => 'nullable|string|max:100',
        ]);

        // Password is hashed automatically by the Dentist model's 'hashed' cast
        $dentist = Dentist::create($data);

        $token = $dentist->createToken('dentist-token')->plainTextToken;

        return response()->json([
            'dentist' => $dentist,
            'token'   => $token,
        ], 201);
    }

    /**
     * Login a dentist.
     */
    public function loginDentist(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $dentist = Dentist::where('email', $request->email)->first();

        if (! $dentist || ! Hash::check($request->password, $dentist->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $dentist->createToken('dentist-token')->plainTextToken;

        return response()->json([
            'dentist' => $dentist,
            'token'   => $token,
        ]);
    }

    /**
     * Logout a dentist (revoke token).
     */
    public function logoutDentist(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * Get the authenticated user's profile (works for both patient and dentist).
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}

