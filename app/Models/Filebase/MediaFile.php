<?php

namespace App\Models\Filebase;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $table = 'mediastore';

    protected $appends = ['href'];

    protected $fillable = ['user_id', 'path', 'name', 'ext', 'link', 'approved'];

    public function scopeUserId($query, $id)
    {
        return $query->where('user_id', $id);
    }

    public function getHrefAttribute()
    {
        return config('app.url') . '/resources/media/' . str_replace(' ', '_', $this->link);
    }

    public function account()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
