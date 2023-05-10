<?php

namespace Ptd;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rating extends Model
{
    protected $table = 'ptd_ratings';

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'ptd_rating_modules', 'rating_id', 'module_id');
    }
}
