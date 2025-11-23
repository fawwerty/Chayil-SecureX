<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function summary()
    {
        $totalClients = Client::count();
        $totalUsers = User::count();

        return response()->json([
            'total_clients' => $totalClients,
            'total_users' => $totalUsers,
        ]);
    }

    public function clients(Request $request)
    {
        $clients = Client::query()
            ->when($request->has('search'), fn($q) => $q->where('company_name', 'like', "%{$request->search}%"))
            ->paginate(15);

        return response()->json($clients);
    }

    public function createClient(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $client = Client::create($data);

        return response()->json($client, 201);
    }

    public function updateClient(Request $request, $clientId)
    {
        $client = Client::findOrFail($clientId);

        $data = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'contact_email' => 'sometimes|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($data);

        return response()->json($client);
    }

    public function deleteClient($clientId)
    {
        $client = Client::findOrFail($clientId);
        $client->delete();

        return response()->json(['message' => 'Client deleted']);
    }
}
