<?php

namespace App\Http\Controllers\User\Regionalgroup;

use App\Http\Controllers\Controller;
use App\Models\Regionalgroup_remove\Regionalgroup;
use App\Models\Regionalgroup_remove\RegionalgroupRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'regionalgroup' => 'required|exists:regionalgroups_regionalgroups,id',
            'reason' => 'required|string',
            'type' => 'required|in:member,guest',
        ]);

        // Check that no other request is currently active
        // for the requested regionalgroup
        if (
            RegionalgroupRequest::where('user_id', $this->_user->id)
                ->where('regionalgroup_id', $validated['regionalgroup'])
                ->exists()
        ) {
            return redirect()
                ->back()
                ->withErrors('TODO: lang(`member.regionalgroup.requesetExists`)');
        }
        // Is the member flag set in any regionalgroup for this user?
        if (
            $validated['type'] == 'member' &&
            $this->_user
                ->regionalgroups()
                ->wherePivot('guest', false)
                ->exists()
        ) {
            return redirect()
                ->back()
                ->withErrors('TODO: lang(`member.regionalgroup.memberElsewhere`)');
        }
        // Wants to be fullmember elsewhere
        if (
            $validated['type'] == 'member' &&
            RegionalgroupRequest::where('user_id', $this->_user->id)
                ->where('type', 'member')
                ->exists()
        ) {
            return redirect()
                ->back()
                ->withErrors('TODO: lang(`member.regionalgroup.memberRequestElsewhere`)');
        }

        $rg = Regionalgroup::find($validated['regionalgroup']);
        if ($this->_user->isMemberOfRegionalgroup($rg) || $this->_user->isGuestOfRegionalgroup($rg)) {
            return redirect()
                ->back()
                ->withErrors('TODO: lang(`member.regionalgroup.memberOrGuestAlready`)');
        }

        $nr = new RegionalgroupRequest();
        $nr->regionalgroup_id = $rg->id;
        $nr->user_id = $this->_user->id;
        $nr->reason = $validated['reason'];
        $nr->type = $validated['type'];
        $nr->save();

        return redirect()
            ->back()
            ->withSuccess('TODO: lang(`member.regionalgroup.requestSend`)');
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'requestId' => 'required|exists:regionalgroups_requests,id',
        ]);

        $rr = RegionalgroupRequest::findOrFail($validated['requestId']);

        if ($rr->user_id !== $this->_user->id) {
            abort(403);
        }

        $rr->delete();

        return redirect()
            ->back()
            ->withSuccess('TODO: lang(`member.regionalgroup.requestRevoked`)');
    }
}
