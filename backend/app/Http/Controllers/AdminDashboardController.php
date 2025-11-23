<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Incident;

class AdminDashboardController extends Controller
{
    public function summary()
    {
        $clientsCount = User::where('role', 'client')->count();
        $incidentsCount = Incident::count();
        $reportsCount = \App\Models\Report::count();

        return response()->json([
            'clients_count' => $clientsCount,
            'incidents_count' => $incidentsCount,
            'reports_count' => $reportsCount,
        ]);
    }
}
