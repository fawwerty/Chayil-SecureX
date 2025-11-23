<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Incident;
use Illuminate\Auth\Access\Response;

class IncidentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Admins can view all, clients can view their own
        return $user->role !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Incident $incident): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Admins can view all incidents
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }
        // Clients can only view incidents they reported
        return $user->id === $incident->reported_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Both admins and clients can create incidents
        return $user->role !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Incident $incident): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Only admins can update incidents
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Incident $incident): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Only admins can delete incidents
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Incident $incident): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Incident $incident): bool
    {
        return false;
    }
}
