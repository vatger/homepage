<?php

namespace App\Policies;

use App\Models\Filebase\MediaFile;
use App\Models\Membership\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MediaPolicy
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
        return $user->can('media.viewAny') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\=Filebase\MediaFile  $mediaFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MediaFile $mediaFile)
    {
        return $user->can('media.view') || $mediaFile->user_id === $user->id ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('media.create') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\=Filebase\MediaFile  $mediaFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MediaFile $mediaFile)
    {
        return $user->can('media.update') ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Membership\User\User  $user
     * @param  \App\Models\=Filebase\MediaFile  $mediaFile
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MediaFile $mediaFile)
    {
        return $user->can('media.delete') || $mediaFile->user_id === $user->id ? Response::allow() : Response::deny();
    }
}
