<?php

namespace Modules\Events\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Events\app\Models\EventType;
use Modules\Users\app\Models\User;

class EventsTypesPolicy
{
    use HandlesAuthorization;
    
    /**
     * Create a new policy instance.
     *
     * @throws \Orion\Exceptions\BindingException
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
    public function view(User $user, EventType $model): bool
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
    public function update(User $user, EventType $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventType $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventType $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventType $model): bool
    {
        return TRUE;
    }
}
