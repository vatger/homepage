<?php

namespace App\Models\Feedback;

use App\Models\Membership\User\User;
use App\Models\Navigation\Station;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControllerFeedback extends Model
{
    use HasFactory;

    protected $table = 'controller_feedback';

    protected $fillable = ['controller_id', 'station_id', 'reporter_id', 'feedback', 'report_date'];

    protected $casts = [
        'report_date' => 'datetime',
    ];

    /**
     * Get the controller that owns the ControllerFeedback
     *
     * @return BelongsTo
     */
    public function controller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'controller_id', 'id');
    }

    /**
     * Get the reporter that owns the ControllerFeedback
     *
     * @return BelongsTo
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id', 'id');
    }

    /**
     * Get the station that owns the ControllerFeedback
     * Can be null(!), if user controlled non-standard position (e.g. EDDF_1_TWR, EDDF__DEL, etc.)
     *
     * @return BelongsTo
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }
}
