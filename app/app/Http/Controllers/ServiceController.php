<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(Service::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $service = Service::create($validated);
        return response()->json($service, 201);
    }

    public function show(string $id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name'             => 'sometimes|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'sometimes|numeric|min:0',
            'duration_minutes' => 'sometimes|integer|min:1',
        ]);

        $service->update($validated);
        return response()->json($service);
    }

    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }
}
