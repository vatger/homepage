<?php

namespace App\Models\Navigation;

use Database\Factories\NavigationFirFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fir extends Model
{
    use HasFactory;

    protected $table = 'nav_firs';

    protected static function newFactory(): Factory
    {
        return NavigationFirFactory::new();
    }
}
