<?php

namespace App\Models\Groups;

use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ServiceRole extends Model
{
    protected $table = 'team_service_roles';

    protected $fillable = ['team_id', 'service_role'];
    protected $appends = ['service_role_name'];

    protected $casts = [
        'service_type' => ServiceRoleType::class,
    ];

    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo|Team
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function getServiceRoleNameAttribute(): ?string
    {
        return match ($this->service_type) {
            ServiceRoleType::TeamspeakServergroup => TeamSpeakWebQuery::getServergroupName(intval($this->role)) ?? '?',
            ServiceRoleType::ForumGroup => '?',
            default => null,
        };
    }

    protected static function booted(): void
    {
        static::saving(function (self $serviceRole) {
            if (
                ServiceRole::where('team_id', $serviceRole->team_id)
                    ->where('service_type', 'LIKE', $serviceRole->service_type)
                    ->where('service_role', 'LIKE', $serviceRole->service_role)
                    ->exists()
            ) {
                throw new \InvalidArgumentException($serviceRole->service_type->value . ' already set.');
            }
        });
    }
}
