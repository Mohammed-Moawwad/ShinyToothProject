<?php

namespace App\Http\Controllers;

use App\Models\DoctorRating;
use Illuminate\Http\Request;

class DoctorRatingController extends Controller
{
    public function index()
    {
        return response()->json(DoctorRating::with(['patient', 'dentist'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'dentist_id'     => 'required|exists:dentists,id',
            'appointment_id' => 'required|exists:appointments,id|unique:doctor_ratings,appointment_id',
            'rating'         => 'required|integer|min:1|max:5',
            'review'         => 'nullable|string',
        ]);

        $rating = DoctorRating::create($validated);
        return response()->json($rating->load(['patient', 'dentist']), 201);
    }

    public function show(string $id)
    {
        $rating = DoctorRating::with(['patient', 'dentist', 'appointment'])->findOrFail($id);
        return response()->json($rating);
    }

    public function update(Request $request, string $id)
    {
        $rating = DoctorRating::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $rating->update($validated);
        return response()->json($rating);
    }

    public function destroy(string $id)
    {
        $rating = DoctorRating::findOrFail($id);
        $rating->delete();
        return response()->json(['message' => 'Rating deleted successfully']);
    }
}
