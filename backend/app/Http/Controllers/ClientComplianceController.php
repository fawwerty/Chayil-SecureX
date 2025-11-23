<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientComplianceController extends Controller
{
    public function index()
    {
        $compliance = Compliance::where('user_id', Auth::id())->get();
        return response()->json($compliance, 200);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'compliance_data' => 'required|string',
        ]);

        $compliance = Compliance::updateOrCreate(
            ['user_id' => Auth::id()],
            ['data' => $validated['compliance_data']]
        );
        
        return response()->json($compliance, 200);
    }

    public function show()
    {
        $compliance = Compliance::where('user_id', Auth::id())->first();
        
        if (!$compliance) {
            return response()->json(['message' => 'No compliance data found'], 404);
        }
        
        return response()->json($compliance, 200);
    }
}