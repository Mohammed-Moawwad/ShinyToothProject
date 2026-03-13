<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        return response()->json(Patient::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:patients,email',
            'password'      => 'required|string|min:8',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female',
            'address'       => 'nullable|string',
            'blood_type'    => 'nullable|string|max:5',
            'place_of_birth'=> 'nullable|string|max:255',
            'nationality'   => 'nullable|string|max:100',
        ]);

        $patient = Patient::create($validated);
        return response()->json($patient, 201);
    }

    public function show(string $id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'email'         => 'sometimes|email|unique:patients,email,' . $id,
            'password'      => 'sometimes|string|min:8',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female',
            'address'       => 'nullable|string',
            'blood_type'    => 'nullable|string|max:5',
            'place_of_birth'=> 'nullable|string|max:255',
            'nationality'   => 'nullable|string|max:100',
        ]);

        $patient->update($validated);
        return response()->json($patient);
    }

    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}
