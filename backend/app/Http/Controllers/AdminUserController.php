<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->has('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->paginate(15);

        return response()->json($users);
    }

    public function updateRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->role_id = $data['role_id'];
        $user->save();

        return response()->json([
            'message' => 'Role updated successfully',
            'user' => $user->load('role'),
        ]);
    }
}
