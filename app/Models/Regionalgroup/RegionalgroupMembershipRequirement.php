<?php

namespace App\Models\Regionalgroup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionalgroupMembershipRequirement extends Model
{
    use HasFactory;

    protected $table = 'regionalgroup_membership_requirements';

    protected $fillable = ['regionalgroup_id', 'requirements'];
}
