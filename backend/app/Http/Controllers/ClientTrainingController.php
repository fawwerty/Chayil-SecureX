<?php

namespace App\Http\Controllers;

use App\Models\TrainingModule;
use App\Models\TrainingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientTrainingController extends Controller
{
    public function modules()
    {
        $modules = TrainingModule::all();
        return response()->json(['data' => $modules], 200);
    }

    public function progress()
    {
        $progress = TrainingProgress::where('user_id', Auth::id())
            ->with('module')
            ->get();
            
        return response()->json(['data' => $progress], 200);
    }

    public function storeProgress(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:training_modules,id',
            'completed' => 'required|boolean',
        ]);

        $progress = TrainingProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'module_id' => $validated['module_id']
            ],
            ['completed' => $validated['completed']]
        );
        
        return response()->json($progress, 201);
    }

    public function showProgress($moduleId)
    {
        $progress = TrainingProgress::where('user_id', Auth::id())
            ->where('module_id', $moduleId)
            ->first();
            
        if (!$progress) {
            return response()->json(['message' => 'Progress not found'], 404);
        }
        
        return response()->json($progress, 200);
    }

    public function updateProgress(Request $request, $moduleId)
    {
        $validated = $request->validate([
            'completed' => 'required|boolean',
            'score' => 'nullable|numeric|min:0|max:100',
        ]);

        $progress = TrainingProgress::where('user_id', Auth::id())
            ->where('module_id', $moduleId)
            ->firstOrFail();
            
        $progress->update($validated);
        
        return response()->json($progress, 200);
    }
}