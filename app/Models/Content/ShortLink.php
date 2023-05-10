<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;

    protected $table = 'short_link';

    protected $fillable = ['shortLink', 'link', 'creator', 'active', 'active_until'];
}
