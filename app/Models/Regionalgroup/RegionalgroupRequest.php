<?php

namespace App\Models\Regionalgroup;

use Illuminate\Database\Eloquent\Model;
use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionalgroupRequest extends Model
{
    protected $table = 'regionalgroups_requests';

    /**
     * The regionalgroup this request is for
     */
    public function regionalgroup(): BelongsTo
    {
        return $this->belongsTo(Regionalgroup::class, 'regionalgroup_id', 'id');
    }

    /**
     * The account that made the request
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
