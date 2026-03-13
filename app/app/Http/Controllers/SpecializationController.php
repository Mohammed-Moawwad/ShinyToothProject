<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index()
    {
        return response()->json(Specialization::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|unique:specializations,name|max:255',
            'description' => 'nullable|string',
        ]);

        $specialization = Specialization::create($validated);
        return response()->json($specialization, 201);
    }

    public function show(string $id)
    {
        $specialization = Specialization::with('dentists')->findOrFail($id);
        return response()->json($specialization);
    }

    public function update(Request $request, string $id)
    {
        $specialization = Specialization::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|string|unique:specializations,name,' . $id . '|max:255',
            'description' => 'nullable|string',
        ]);

        $specialization->update($validated);
        return response()->json($specialization);
    }

    public function destroy(string $id)
    {
        $specialization = Specialization::findOrFail($id);
        $specialization->delete();
        return response()->json(['message' => 'Specialization deleted successfully']);
    }
}
