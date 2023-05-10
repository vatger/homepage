<?php

namespace App\Http\Controllers\Administration\Regionalgroup;

use App\Http\Controllers\Controller;
use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Http\Request;

class RegionalgroupController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function updateNavigators(Request $request, Regionalgroup $regionalgroup)
    {
        $this->authorize('update', $regionalgroup);

        if (!$request->has('newNavigator')) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Failed to get \'newNavigator\' key!');
        }

        $regionalgroup->navigators()->attach(User::where('id', $request->get('newNavigator'))->first());

        return redirect()
            ->back()
            ->withSuccess('Navigators Updated!');
    }
}
