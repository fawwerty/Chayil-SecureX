<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Events\IncidentCreated;
use Illuminate\Http\Request;

class AdminIncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::with('reporter')->get();
        return response()->json($incidents, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'reported_by' => 'required|exists:users,id',
            'status' => 'nullable|string|in:open,in_progress,resolved,closed',
            'priority' => 'nullable|string|in:low,medium,high,critical',
        ]);

        $validated['status'] = $validated['status'] ?? 'open';

        $incident = Incident::create($validated);
        
        event(new IncidentCreated($incident));
        
        return response()->json($incident, 201);
    }

    public function show(Incident $incident)
    {
        return response()->json($incident->load('reporter'), 200);
    }

    public function update(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|string|in:open,in_progress,resolved,closed',
            'priority' => 'sometimes|string|in:low,medium,high,critical',
        ]);

        $incident->update($validated);
        
        return response()->json($incident, 200);
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();
        
        return response()->json(['message' => 'Incident deleted successfully'], 200);
    }
}