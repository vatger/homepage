<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';

    protected $fillable = ['name', 'logo_url', 'link_url', 'description_de', 'description_en'];

    public function getDescriptionAttribute()
    {
        if ((Auth::check() && Auth::user()->settings->language == 'de') || (Session::has('language') && Session::get('language') == 'de')) {
            return $this->description_de;
        } else {
            return $this->description_en;
        }
    }
}
