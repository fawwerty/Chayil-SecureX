<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Load client relationship
        if (!$user->relationLoaded('client')) {
            $user->load('client');
        }
        
        // If no client record exists, return zeros
        if (!$user->client) {
            return response()->json([
                'incidents_count' => 0,
                'reports_count' => 0,
            ]);
        }
        
        $incidentsCount = $user->client->incidents()->count();
        $reportsCount = $user->client->reports()->count();

        return response()->json([
            'incidents_count' => $incidentsCount,
            'reports_count' => $reportsCount,
        ]);
    }
}
