<?php

namespace App\Models\Groups;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait IsGroupTrait
{
    public function group(): HasOne|Group
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    public function members(): BelongsToMany|User
    {
        $g = $this->group()->first();
        return $g->belongsToMany(User::class, 'model_has_groups', 'group_id', 'model_id');
    }
}
