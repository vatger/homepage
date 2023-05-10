<?php

namespace App\Models\Navigation;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $table = 'navigation_charts';

    public function aerodromes()
    {
        return $this->belongsToMany(Aerodrome::class, 'navigation_aerodrome_chart', 'chart_id', 'aerodrome_id');
    }

    public function scopeAirac($query, $airac = '')
    {
        if ($airac === '') {
            $airac = Carbon::now()
                ->utc()
                ->format('ym');
        }
        return $query->where('airac', $airac);
    }

    public function scopePublished($query, $published = true)
    {
        return $query->where('published', $published);
    }
}
