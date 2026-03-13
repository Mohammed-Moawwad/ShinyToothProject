<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(
            Appointment::with(['patient', 'dentist', 'service'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'dentist_id'       => 'required|exists:dentists,id',
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'status'           => 'nullable|in:scheduled,completed,cancelled',
            'notes'            => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);
        return response()->json($appointment->load(['patient', 'dentist', 'service']), 201);
    }

    public function show(string $id)
    {
        $appointment = Appointment::with(['patient', 'dentist', 'service', 'payment', 'rating'])
            ->findOrFail($id);
        return response()->json($appointment);
    }

    public function update(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'patient_id'       => 'sometimes|exists:patients,id',
            'dentist_id'       => 'sometimes|exists:dentists,id',
            'service_id'       => 'sometimes|exists:services,id',
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes|date_format:H:i',
            'status'           => 'nullable|in:scheduled,completed,cancelled',
            'notes'            => 'nullable|string',
        ]);

        $appointment->update($validated);
        return response()->json($appointment);
    }

    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}
