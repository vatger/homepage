<?php

namespace Ptd;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ModuleSession extends Model
{
    protected $table = 'ptd_module_sessions';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'starts_at' => 'datetime',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function registered_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ptd_module_session_registrations', 'session_id', 'user_id');
    }
}
