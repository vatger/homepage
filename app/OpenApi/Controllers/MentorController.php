<?php

namespace App\OpenApi\Controllers;

use App\Models\Regionalgroup\Regionalgroup;

class MentorController extends ApiController
{
    public function listMentors()
    {
        return Regionalgroup::all()
            ->flatMap(function ($rg) {
                return $rg
                    ->mentors()
                    ->get()
                    ->map(function ($user) {
                        return $user->id;
                    });
            })
            ->unique();
    }
}
