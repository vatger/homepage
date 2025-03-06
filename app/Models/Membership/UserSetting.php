<?php

namespace App\Models\Membership;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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
        $policies = Cache::remember('usersetting.policies.toaggree', 60 * 10, function () {
            $data = File::get(storage_path('app/configurations/policies.json'));
            $json = json_decode($data, false);
            $array = array_filter($json, fn ($item) => $item->needs_approval == true);

            return array_map(fn ($item) => $item->id, $array);
        });

        // todo
        return false;
    }

    private function getAgreedAt(string $id): ?Carbon
    {
        $json = json_decode($this->policies);
        if (empty($json) || ! is_array($json)) {
            return null;
        }


        return null;
    }
}
