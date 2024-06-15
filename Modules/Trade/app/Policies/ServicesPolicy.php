<?php

namespace Modules\Trade\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Trade\App\Models\Tradeable;
use Modules\Users\app\Models\User;

class ServicesPolicy extends TradeablesPolicy
{
    use HandlesAuthorization;
    
    /**
     * Create a new policy instance.
     *
     * @throws \Orion\Exceptions\BindingException
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return parent::viewAny($user) && TRUE;
    }
    
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tradeable $model): bool
    {
        return parent::view($user, $model) && TRUE;
    }
    
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return parent::create($user) && TRUE;
    }
    
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tradeable $model): bool
    {
        return parent::update($user, $model) && TRUE;
    }
    
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tradeable $model): bool
    {
        return parent::delete($user, $model) && TRUE;
    }
    
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tradeable $model): bool
    {
        return parent::restore($user, $model) && TRUE;
    }
    
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tradeable $model): bool
    {
        return parent::forceDelete($user, $model) && TRUE;
    }
}
