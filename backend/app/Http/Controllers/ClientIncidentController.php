<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Events\IncidentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientIncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::where('reported_by', Auth::id())
            ->with('reporter')
            ->get();
            
        return response()->json($incidents, 200);
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|string|in:low,medium,high,critical',
        ]);

        $validated['reported_by'] = Auth::id();
        $validated['status'] = 'open';

        $incident = Incident::create($validated);
        
        event(new IncidentCreated($incident));
        
        return response()->json($incident, 201);
    }

    public function show(Incident $incident)
    {
        if ($incident->reported_by !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json($incident->load('reporter'), 200);
    }

    public function update(Request $request, Incident $incident)
    {
        if ($incident->reported_by !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $incident->update($validated);
        
        return response()->json($incident, 200);
    }
}