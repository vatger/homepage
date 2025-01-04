<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class GdprRemoval extends Model
{
    protected $table = 'gdpr_removals';

    public $timestamps = false;

    public $appends = ['user', 'pending_services', 'completed_services', 'running'];

    protected function casts(): array
    {
        return [
            'service_data' => 'object',
        ];
    }

    public function getUserAttribute(): ?User
    {
        return User::find($this->user_id);
    }

    public function getPendingServicesAttribute(): array
    {
        $services = collect(json_decode(File::get(storage_path('app/configurations/gdpr-removal-services.json'))));
        $service_names = $services->map(fn ($service) => $service->name)->toArray();
        $completed = collect($this->service_data)->filter(fn ($service_data) => $service_data->completed_at != null)->map(fn ($service) => $service->name)->toArray();

        return array_diff($service_names, $completed);
    }

    public function getCompletedServicesAttribute(): array
    {
        return collect($this->service_data)->filter(fn ($service) => $service->completed_at != null)->map(fn ($service) => $service->name)->toArray();
    }

    public function getRunningAttribute(): bool
    {
        if ($this->canceled_at != null) {
            return false;
        }

        return $this->completed_at == null || ! empty($this->getPendingServicesAttribute());
    }
}
