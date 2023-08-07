<?php

namespace App\Models\Groups;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait IsGroupTrait
{
    public function group(): HasOne|Group
    {
        return $this->hasOne(Group::class, 'group_id', 'id');
    }

    public function members(): HasMany|User
    {
        // todo check
        return $this->hasManyThrough(User::class, Group::class, 'group_id', 'model_id', 'id', 'id');
    }
}
