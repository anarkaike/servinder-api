<?php

namespace Modules\Financial\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Financial\app\Models\Wallet;
use Modules\Users\app\Models\User;

class WalletsPolicy
{
    use HandlesAuthorization;
    
    /**
     * Create a new policy instance.
     *
     */
    public function __construct()
    {
    }
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wallet $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wallet $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wallet $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Wallet $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Wallet $model): bool
    {
        return TRUE;
    }
}
