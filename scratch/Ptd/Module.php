<?php

namespace Ptd;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $table = 'ptd_modules';

    public function requirements(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'ptd_module_requirements', 'module_id', 'requirement_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ModuleSession::class, 'module_id', 'id');
    }
}
