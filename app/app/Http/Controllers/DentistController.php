<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index()
    {
        return response()->json(Dentist::with('specializations')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:dentists,email',
            'password'         => 'required|string|min:8',
            'phone'            => 'nullable|string|max:20',
            'salary'           => 'nullable|numeric|min:0',
            'hire_date'        => 'nullable|date',
            'status'           => 'nullable|in:active,inactive,on_leave',
            'place_of_birth'   => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'university'       => 'nullable|string|max:255',
        ]);

        $dentist = Dentist::create($validated);
        return response()->json($dentist, 201);
    }

    public function show(string $id)
    {
        $dentist = Dentist::with('specializations')->findOrFail($id);
        return response()->json($dentist);
    }

    public function update(Request $request, string $id)
    {
        $dentist = Dentist::findOrFail($id);

        $validated = $request->validate([
            'name'             => 'sometimes|string|max:255',
            'email'            => 'sometimes|email|unique:dentists,email,' . $id,
            'password'         => 'sometimes|string|min:8',
            'phone'            => 'nullable|string|max:20',
            'salary'           => 'nullable|numeric|min:0',
            'hire_date'        => 'nullable|date',
            'status'           => 'nullable|in:active,inactive,on_leave',
            'place_of_birth'   => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'university'       => 'nullable|string|max:255',
        ]);

        $dentist->update($validated);
        return response()->json($dentist);
    }

    public function destroy(string $id)
    {
        $dentist = Dentist::findOrFail($id);
        $dentist->delete();
        return response()->json(['message' => 'Dentist deleted successfully']);
    }
}
