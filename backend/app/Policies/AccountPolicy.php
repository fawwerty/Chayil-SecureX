<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccountPolicy
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
        // Only admins can view all accounts
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Admins can view any account, users can view their own
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }
        return $user->id === $model->id;
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
        // Only admins can create accounts
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Admins can update any account, users can update their own
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Load role relationship if not loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        // Only admins can delete accounts
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
