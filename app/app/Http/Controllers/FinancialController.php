<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index()
    {
        return response()->json(Financial::orderBy('year')->orderBy('month')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month'         => 'required|integer|min:1|max:12',
            'year'          => 'required|integer|min:2000',
            'total_revenue' => 'required|numeric|min:0',
            'total_costs'   => 'required|numeric|min:0',
            'profit'        => 'required|numeric',
            'notes'         => 'nullable|string',
        ]);

        $financial = Financial::create($validated);
        return response()->json($financial, 201);
    }

    public function show(string $id)
    {
        $financial = Financial::findOrFail($id);
        return response()->json($financial);
    }

    public function update(Request $request, string $id)
    {
        $financial = Financial::findOrFail($id);

        $validated = $request->validate([
            'month'         => 'sometimes|integer|min:1|max:12',
            'year'          => 'sometimes|integer|min:2000',
            'total_revenue' => 'sometimes|numeric|min:0',
            'total_costs'   => 'sometimes|numeric|min:0',
            'profit'        => 'sometimes|numeric',
            'notes'         => 'nullable|string',
        ]);

        $financial->update($validated);
        return response()->json($financial);
    }

    public function destroy(string $id)
    {
        $financial = Financial::findOrFail($id);
        $financial->delete();
        return response()->json(['message' => 'Financial record deleted successfully']);
    }
}
