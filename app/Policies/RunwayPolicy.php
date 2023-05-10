<?php

namespace App\Policies;

use App\Models\Membership\User\User;
use App\Models\Navigation\Navaid;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RunwayPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('navigation.aerodromes.viewAny') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Navaid $runway
     * @return Response|bool
     */
    public function view(User $user, Navaid $runway)
    {
        return $user->can('navigation.aerodromes.view') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('navigation.aerodromes.create') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Navaid $runway
     * @return Response|bool
     */
    public function update(User $user, Navaid $runway)
    {
        return $user->can('navigation.aerodromes.update') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Navaid $runway
     * @return Response|bool
     */
    public function delete(User $user, Navaid $runway)
    {
        return $user->can('navigation.aerodromes.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Navaid $runway
     * @return Response|bool
     */
    public function restore(User $user, Navaid $runway)
    {
        return $user->can('navigation.aerodromes.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Navaid $runway
     * @return Response|bool
     */
    public function forceDelete(User $user, Navaid $runway)
    {
        return $user->can('navigation.aerodromes.delete') ? Response::allow() : Response::deny();
    }
}
