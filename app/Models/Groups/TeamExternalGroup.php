<?php

namespace App\Models\Groups;

use App\Libraries\BookstackLibrary;
use App\Libraries\NextcloudLibrary;
use App\Libraries\OSTicketLibrary;
use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Libraries\VikunjaLibrary;
use App\Libraries\XenForoLibrary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Throwable;

class TeamExternalGroup extends Model
{
    protected $table = 'team_external_groups';

    protected $fillable = ['team_id', 'external_group'];

    protected $appends = ['external_group_name'];

    protected $casts = [
        'external_group_type' => TeamExternalGroupType::class,
    ];

    public function team(): BelongsTo|Team
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function getExternalGroupNameAttribute(): ?string
    {
        $name = Cache::remember(
            'team-external-group-name:'.$this->external_group_type?->value.':'.$this->external_group,
            60,
            function (): string {
                try {
                    return match ($this->external_group_type) {
                        TeamExternalGroupType::TeamspeakServergroup => TeamSpeakWebQuery::getServergroupName((int) $this->external_group),
                        TeamExternalGroupType::SupportGroup => OSTicketLibrary::get_group_name((int) $this->external_group),
                        TeamExternalGroupType::BookstackGroup => BookstackLibrary::get_group_name((int) $this->external_group),
                        TeamExternalGroupType::NextcloudGroup => NextcloudLibrary::get_group_name($this->external_group),
                        TeamExternalGroupType::VikunjaGroup => VikunjaLibrary::get_group_name((int) $this->external_group),
                        TeamExternalGroupType::ForumGroup => XenForoLibrary::get_group_name((int) $this->external_group),
                        default => null,
                    } ?? '__unavailable__';
                } catch (Throwable) {
                    return '__unavailable__';
                }
            },
        );

        return $name === '__unavailable__' ? null : $name;
    }

    protected static function booted(): void
    {
        static::saving(function (self $externalGroup): void {
            if (self::where('team_id', $externalGroup->team_id)
                ->whereKeyNot($externalGroup->getKey())
                ->where('external_group_type', $externalGroup->external_group_type)
                ->where('external_group', $externalGroup->external_group)
                ->exists()) {
                throw new \InvalidArgumentException($externalGroup->external_group_type->value.' already set.');
            }
        });
    }
}
