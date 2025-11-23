<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $client = Client::create($validated);
        
        return response()->json($client, 201);
    }

    public function show(Client $client)
    {
        return response()->json($client, 200);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'contact_email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($validated);
        
        return response()->json($client, 200);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }
}