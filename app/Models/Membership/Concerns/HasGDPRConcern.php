<?php

namespace App\Models\Membership\Concerns;

use App\Models\Membership\GdprRemoval;


trait HasGDPRConcern
{
    public function isCurrentlyInRemoval(): bool
    {
        return GdprRemoval::where('user_id', $this->id)->whereNull('completed_at')->exists();
    }


}
