<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;

class WebAuthController extends Controller
{
    /**
     * Handle web-based login (for admin panel)
     */
    public function loginWeb(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to find and authenticate the patient
        $patient = Patient::where('email', $validated['email'])->first();

        if (!$patient || !Hash::check($validated['password'], $patient->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // Login the patient
        Auth::guard('web')->login($patient);
        $request->session()->regenerate();

        // Check if the user is an admin
        $adminEmail = config('admin.email');
        if ($patient->email === $adminEmail) {
            return redirect('/admin/dashboard');
        }

        // Regular patient, redirect to patient dashboard
        return redirect('/patient/dashboard');
    }

    /**
     * Handle logout
     */
    public function logoutWeb(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
