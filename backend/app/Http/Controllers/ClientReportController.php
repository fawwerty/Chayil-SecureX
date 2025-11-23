<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ClientReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $reports = Report::where('created_by', $user->id)->paginate(15);
        return response()->json($reports);
    }
}
