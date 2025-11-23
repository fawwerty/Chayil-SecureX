<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return response()->json($settings, 200);
    }

    public function update(Request $request)
    {
        $settings = $request->all();
        
        $updated = [];
        
        foreach ($settings as $key => $value) {
            $setting = Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            $updated[] = $setting;
        }
        
        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => $updated
        ], 200);
    }

    public function show($key)
    {
        $setting = Setting::where('key', $key)->first();
        
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }
        
        return response()->json($setting, 200);
    }

    public function destroy($key)
    {
        $setting = Setting::where('key', $key)->first();
        
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }
        
        $setting->delete();
        
        return response()->json(['message' => 'Setting deleted successfully'], 200);
    }
}