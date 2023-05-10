<?php

namespace App\Models\Regionalgroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionalgroupTemplate extends Model
{
    protected $table = 'regionalgroups_templates';

    /**
     * The regionalgroup this template is for
     */
    public function regionalgroup(): BelongsTo
    {
        return $this->belongsTo(Regionalgroup::class, 'regionalgroup_id', 'id')->select(['id', 'name']);
    }
}
