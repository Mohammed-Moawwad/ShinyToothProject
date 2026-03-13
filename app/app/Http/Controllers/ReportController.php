<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json(Report::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:monthly,yearly,custom',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'generated_by' => 'required|string|max:255',
            'data'         => 'nullable|array',
        ]);

        $report = Report::create($validated);
        return response()->json($report, 201);
    }

    public function show(string $id)
    {
        $report = Report::findOrFail($id);
        return response()->json($report);
    }

    public function update(Request $request, string $id)
    {
        $report = Report::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'type'         => 'sometimes|in:monthly,yearly,custom',
            'start_date'   => 'sometimes|date',
            'end_date'     => 'sometimes|date|after_or_equal:start_date',
            'generated_by' => 'sometimes|string|max:255',
            'data'         => 'nullable|array',
        ]);

        $report->update($validated);
        return response()->json($report);
    }

    public function destroy(string $id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return response()->json(['message' => 'Report deleted successfully']);
    }
}
