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

    public static array $allowed_service_types = ['board.group', 'ts.servergroup', 'kb.group'];

    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo|Team
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function getServiceRoleNameAttribute(): ?string
    {
        switch ($this->service_type) {
            case 'ts.servergroup':
                return TeamSpeakWebQuery::getServergroupName(intval($this->role));
            case 'board.group':
                return null;
            case 'kb.group':
                return null;
            default:
                return null;
        }
    }

    protected static function booted(): void
    {
        static::saving(function (self $serviceRole) {
            if (!in_array($serviceRole->service_type, self::$allowed_service_types)) {
                throw new \InvalidArgumentException($serviceRole->service_type . ' is not an allowed service_type.');
            }
            if (
                ServiceRole::where('team_id', $serviceRole->team_id)
                    ->where('service_type', 'LIKE', $serviceRole->service_type)
                    ->where('service_role', 'LIKE', $serviceRole->service_role)
                    ->exists()
            ) {
                throw new \InvalidArgumentException($serviceRole->service_type . ' already set.');
            }
        });
    }
}
