<?php

namespace App\Policies;

use App\Models\Membership\User\User;
use App\Models\Navigation\Chart;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ChartPolicy
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
        return $user->can('navigation.charts.viewAny') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Chart  $chart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Chart $chart)
    {
        return $user->can('navigation.charts.view') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('navigation.charts.create') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Chart  $chart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Chart $chart)
    {
        return $user->can('navigation.charts.update') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Chart  $chart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Chart $chart)
    {
        return $user->can('navigation.charts.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Chart  $chart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Chart $chart)
    {
        return $user->can('navigation.charts.delete') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Navigation\Chart  $chart
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Chart $chart)
    {
        return $user->can('navigation.charts.delete') ? Response::allow() : Response::deny();
    }
}
