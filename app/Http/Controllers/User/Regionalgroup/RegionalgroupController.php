<?php

namespace App\Http\Controllers\User\Regionalgroup;

use App\Http\Controllers\Controller;
use App\Libraries\Membership\MembershipLibrary;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegionalgroupController extends Controller
{
    /**
     * Display the membership view of a given regionalgroup
     */
    public function show(Request $request, Regionalgroup $regionalgroup): \Illuminate\View\View
    {
        return $this->prepareView('homepage.members.regionalgroup.index')->with('regionalgroup', $regionalgroup);
    }

    /**
     * Cancel membership at requested regionalgroup.
     * This will remove the current user from the regionalgroup
     * and trigger the MembershipLibrary update function
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'regionalgroup' => 'required|exists:regionalgroups_regionalgroups,id',
        ]);

        $rg = Regionalgroup::find($validated['regionalgroup']);

        if ($this->_user->isMemberOfRegionalgroup($rg) || $this->_user->isGuestOfRegionalgroup($rg)) {
            $rg->accounts()->detach($this->_user);

            MembershipLibrary::handleMembershipChange($this->_user);
        }

        return redirect()
            ->route('member.profile')
            ->with(['success' => '(lang) You have left the ' . $rg->name . ' successfully!']);
    }
}
