<?php

namespace App\Models\Groups;

use App\Libraries\BookstackLibrary;
use App\Libraries\NextcloudLibrary;
use App\Libraries\OSTicketLibrary;
use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Libraries\VikunjaLibrary;
use App\Libraries\XenForoLibrary;
use Exception;
use Illuminate\Database\Eloquent\Model;

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
        try {
            return match ($this->service_type) {
                ServiceRoleType::TeamspeakServergroup => TeamSpeakWebQuery::getServergroupName(intval($this->service_role)) ?? '?',
                ServiceRoleType::SupportGroup => OSTicketLibrary::get_group_name(intval($this->service_role)) ?? '?',
                ServiceRoleType::BookstackGroup => BookstackLibrary::get_group_name(intval($this->service_role)) ?? '?',
                ServiceRoleType::NextcloudGroup => NextcloudLibrary::get_group_name($this->service_role) ?? '?',
                ServiceRoleType::VikunjaGroup => VikunjaLibrary::get_group_name(intval($this->service_role)) ?? '?',
                ServiceRoleType::ForumGroup => XenForoLibrary::get_group_name(intval($this->service_role)) ?? '?',
                default => null,
            };
        } catch (Exception $e) {
        }

        return null;
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
                throw new \InvalidArgumentException($serviceRole->service_type->value.' already set.');
            }
        });
    }
}
