<?php

namespace Modules\Events\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Events\app\Models\Event;
use Modules\Users\app\Models\User;

class EventsPolicy
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
    public function view(User $user, Event $model): bool
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
    public function update(User $user, Event $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $model): bool
    {
        return TRUE;
    }
    
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $model): bool
    {
        return TRUE;
    }
}
