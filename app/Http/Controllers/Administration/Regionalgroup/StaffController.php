<?php

namespace App\Http\Controllers\Administration\Regionalgroup;

use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Http\Request;

class StaffController extends RegionalgroupController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setChief(Request $request, Regionalgroup $regionalgroup)
    {
        $this->authorize('update', $regionalgroup);

        $newChief = User::findOrFail($request->id);

        if (!$regionalgroup->members->contains($newChief)) {
            return response(false);
        }

        if ($regionalgroup->deputy_id == $newChief->id) {
            $regionalgroup->deputy_id = null;
        }

        $regionalgroup->chief_id = $newChief->id;
        $regionalgroup->save();

        return true;
    }
    public function setDeputy(Request $request, Regionalgroup $regionalgroup)
    {
        $this->authorize('update', $regionalgroup);

        $newDeputy = User::findOrFail($request->id);

        if (!$regionalgroup->members->contains($newDeputy)) {
            return response(false);
        }

        if ($regionalgroup->chief_id == $newDeputy->id) {
            $regionalgroup->chief_id = null;
        }

        $regionalgroup->deputy_id = $newDeputy->id;
        $regionalgroup->save();

        return true;
    }
}
