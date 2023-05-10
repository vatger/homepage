<?php

namespace App\Policies;

use App\Models\Membership\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
        return $user->can('membership.users.viewAny')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.users.accessDenied`)');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\User\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        // TODO @Kramer, was für einen Sinn hat die Zeile? Insb. der Vergleich $user->id != $model->id? Soll ein Staffler nicht auf sein eigenes Profil zugreifen dürfen? Wenn ja, wieso - geht doch auf der aktuellen Seite auch und
        // TODO mission critical ist davon sicherlich nichts.
        return $user->can('membership.users.view') && $user->id != $model->id
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.users.accessDenied`)');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('membership.users.create')
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.users.accessDenied`)');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\User\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        return $user->can('membership.users.update') && $user->id != $model->id
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.users.accessDenied`)');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\User\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return $user->can('membership.users.delete') && $user->id != $model->id
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.users.accessDenied`)');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\User\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        return $user->can('membership.users.delete') && $user->id != $model->id
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.users.accessDenied`)');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\Membership\User\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        return $user->can('membership.users.delete') && $user->id != $model->id
            ? Response::allow()
            : Response::deny('TODO: lang(`administration.membership.users.accessDenied`)');
    }
}
