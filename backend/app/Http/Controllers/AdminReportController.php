<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('creator')->paginate(15);
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'created_by' => 'required|exists:users,id',
        ]);

        $report = Report::create($data);

        return response()->json($report, 201);
    }

    public function export(Request $request)
    {
        $reports = Report::all(['title', 'content', 'created_by', 'created_at']);
        return response()->json($reports);
    }
}
