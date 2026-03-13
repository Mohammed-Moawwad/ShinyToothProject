<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(Payment::with(['appointment', 'patient'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id|unique:payments,appointment_id',
            'patient_id'     => 'required|exists:patients,id',
            'amount'         => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,credit_card,debit_card',
            'status'         => 'nullable|in:pending,paid,refunded',
        ]);

        $payment = Payment::create($validated);
        return response()->json($payment->load(['appointment', 'patient']), 201);
    }

    public function show(string $id)
    {
        $payment = Payment::with(['appointment', 'patient'])->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'amount'         => 'sometimes|numeric|min:0',
            'payment_date'   => 'sometimes|date',
            'payment_method' => 'sometimes|in:cash,credit_card,debit_card',
            'status'         => 'nullable|in:pending,paid,refunded',
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
