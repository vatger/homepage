<?php

namespace App\Policies;

use App\Models\Membership\Role;
use App\Models\Membership\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RolePolicy
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
        return $user->can('membership.roles.viewAny')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.roles.accessDenied`)');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Role $role)
    {
        return $user->can('membership.roles.view') ? Response::allow() : Response::deny('TODO: lang(`administration.membership.roles.accessDenied`)');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('membership.roles.create')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.roles.accessDenied`)');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Role $role)
    {
        return $user->can('membership.roles.update')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.roles.accessDenied`)');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Role $role)
    {
        return $user->can('membership.roles.delete')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.roles.accessDenied`)');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Role $role)
    {
        return $user->can('membership.roles.delete')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.roles.accessDenied`)');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\Role  $role
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Role $role)
    {
        return $user->can('membership.roles.delete')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.roles.accessDenied`)');
    }
}
