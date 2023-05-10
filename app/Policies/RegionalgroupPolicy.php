<?php

namespace App\Policies;

use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RegionalgroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if a user can access the Regionalgroup part
     * of the administration
     *
     * @param User $user
     * @return Response
     */
    public function viewAny(User $user)
    {
        $isStaffAtSomeRegionalgroup = false;
        $regionalgroups = Regionalgroup::all();
        foreach ($regionalgroups as $rg) {
            if ($user->id === $rg->chief_id || $user->id === $rg->deputy_id) {
                $isStaffAtSomeRegionalgroup = true;
                break;
            }
        }

        return $user->can('regionalgroup.viewAny') || $isStaffAtSomeRegionalgroup
            ? Response::allow()
            : Response::deny('You are not allowed to manipulate regionalgroups!');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Regionalgroup\Regionalgroup  $regionalgroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Regionalgroup $regionalgroup)
    {
        return $user->can('regionalgroup.view') || $regionalgroup->chief_id === $user->id || $regionalgroup->deputy_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to manipulate regionalgroups!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('regionalgroup.create') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Regionalgroup\Regionalgroup  $regionalgroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Regionalgroup $regionalgroup)
    {
        return $user->can('regionalgroup.update') || $regionalgroup->chief_id === $user->id || $regionalgroup->deputy_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to manipulate regionalgroups!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Regionalgroup\Regionalgroup  $regionalgroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Regionalgroup $regionalgroup)
    {
        return $user->can('regionalgroup.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Regionalgroup\Regionalgroup  $regionalgroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Regionalgroup $regionalgroup)
    {
        return $user->can('regionalgroup.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Regionalgroup\Regionalgroup  $regionalgroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Regionalgroup $regionalgroup)
    {
        return $user->can('regionalgroup.delete') ? Response::allow() : Response::deny();
    }
}
