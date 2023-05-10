<?php

namespace App\Models\Membership;

use App\Models\Forum\ForumGroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    public function forumGroups(): BelongsToMany
    {
        return $this->belongsToMany(ForumGroup::class, 'forumgroup_group', 'forumgroup_id', 'role_id');
    }
}
