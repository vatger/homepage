<?php

namespace App\Models\Regionalgroup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightInformationRegion extends Model
{
    protected $table = 'regionalgroups_firs';

    /**
     * All regionalgroups assigned to this fir
     */
    public function regionalgroups(): HasMany
    {
        return $this->hasMany(Regionalgroup::class, 'fir_id', 'id');
    }
}
