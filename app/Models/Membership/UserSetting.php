<?php

namespace App\Models\Membership;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;
use JsonException;

class UserSetting extends Model
{
    protected $primaryKey = 'user_id';

    protected $table = 'user_settings';

    protected $fillable = ['language', 'forum_id', 'policies'];

    protected $appends = ['agreed'];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getAgreedAttribute(): bool
    {
        $policies = self::getPolicies(true);
        foreach ($policies as $policy) {
            $user_agreed = $this->getAgreedAt($policy->id);
            if ($user_agreed == null) {
                return false;
            }
            if (! $user_agreed->isAfter(Carbon::create($policy->last_update))) {
                return false;
            }
        }

        return true;
    }

    public function agreeTo(string $policy_id, bool $decline = false): void
    {
        $policies = self::getPolicies(true);
        if (! array_any($policies, fn ($policy) => $policy->id == $policy_id)) {
            return;
        }
        $agreed_policies = $this->getMyPolices();
        $agreed_policies = array_filter($agreed_policies, fn ($item) => $item->id != $policy_id);

        if (! $decline) {
            $agreed_policies[] = (object) ['id' => $policy_id, 'date' => Carbon::now()->toISO8601String()];
        }

        $this->policies = json_encode(array_values($agreed_policies));
        $this->save();
    }

    public static function getPolicies(bool $needs_approval = false, bool $id_only = false): array
    {
        try {
            $data = File::get(storage_path('app/configurations/policies.json'));
            $policies = json_decode($data, false, flags: JSON_THROW_ON_ERROR);
        } catch (FileNotFoundException|JsonException $e) {
            return [];
        }
        if ($needs_approval) {
            $policies = array_filter($policies, fn ($policy) => $policy->needs_approval == true);
        }
        if ($id_only) {
            $policies = array_map(fn ($policy) => $policy->id, $policies);
        }

        return $policies;
    }

    public function getAgreedAt(string $id): ?Carbon
    {
        $json = $this->getMyPolices();
        $item = array_find($json, fn ($item) => $item->id == $id);
        if ($item == null || $item->date == null) {
            return null;
        }

        return Carbon::create($item->date);
    }

    private function getMyPolices(): array
    {
        $json = json_decode($this->policies);
        if (empty($json) || ! is_array($json)) {
            return [];
        }

        return array_values($json);
    }
}
