<?php

namespace App\Models;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';

    protected $fillable = ['created_by', 'name', 'logo_url', 'link_url', 'description_de', 'description_en'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function getDescriptionAttribute()
    {
        if ((Auth::check() && Auth::user()->settings->language == 'de') || (Session::has('language') && Session::get('language') == 'de')) {
            return $this->description_de;
        } else {
            return $this->description_en;
        }
    }
}
