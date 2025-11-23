<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        return $user->role && $user->role->name === 'Admin';
    }

    public function view(User $user, Client $client): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }
        return $user->id === $client->user_id;
    }

    public function create(User $user): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        return $user->role && $user->role->name === 'Admin';
    }

    public function update(User $user, Client $client): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }
        return $user->id === $client->user_id;
    }

    public function delete(User $user, Client $client): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        return $user->role && $user->role->name === 'Admin';
    }
}
