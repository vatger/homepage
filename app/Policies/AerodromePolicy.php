<?php

namespace App\Policies;

use App\Models\Membership\User\User;
use App\Models\Navigation\Aerodrome;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AerodromePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('navigation.aerodromes.viewAny') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Aerodrome  $aerodrome
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Aerodrome $aerodrome)
    {
        return $user->can('navigation.aerodromes.view') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('navigation.aerodromes.create') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Aerodrome  $aerodrome
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Aerodrome $aerodrome)
    {
        return $user->can('navigation.aerodromes.update') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Aerodrome  $aerodrome
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Aerodrome $aerodrome)
    {
        return $user->can('navigation.aerodromes.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Aerodrome  $aerodrome
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Aerodrome $aerodrome)
    {
        return $user->can('navigation.aerodromes.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Aerodrome  $aerodrome
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Aerodrome $aerodrome)
    {
        return $user->can('navigation.aerodromes.delete') ? Response::allow() : Response::deny();
    }
}
