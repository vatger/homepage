<?php

namespace App\Http\Controllers\Administration\Regionalgroup;

use App\Libraries\Membership\MembershipLibrary;
use App\Models\Regionalgroup\Regionalgroup;
use App\Models\Regionalgroup\RegionalgroupRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RequestController extends RegionalgroupController
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a detailed page for a request
     * @param Request $request
     * @param Regionalgroup $regionalgroup
     * @param RegionalgroupRequest $regionagroupRequest
     * @return View
     */
    public function show(Request $request, Regionalgroup $regionalgroup, RegionalgroupRequest $regionalgroupRequest): View
    {
        $this->authorize('update', $regionalgroup);

        return $this->prepareView('administration.regionalgroup.request.view')
            ->with('regionalgroup', $regionalgroup)
            ->with('regionalgroupRequest', $regionalgroupRequest);
    }

    /**
     * Update a request.
     * AKA handle a join request
     * @param Request $request
     * @param Regionalgroup $regionalgroup
     * @param RegionalgroupRequest
     * @return RedirectResponse
     */
    public function update(Request $request, Regionalgroup $regionalgroup, RegionalgroupRequest $regionalgroupRequest): RedirectResponse
    {
        $this->authorize('update', $regionalgroup);

        // If the user wants to join a regionalgroup as "guest" we can accept the request without further checks
        if ($regionalgroupRequest->type == 'guest') {
            $regionalgroup->accounts()->attach($regionalgroupRequest->account, ['guest' => true, 'pilot' => true, 'controller' => true]);

            // TODO: Send notifications to user

            // Delete the request, as we do not need it anymore
            $regionalgroupRequest->delete();

            return redirect()
                ->route('administration.regionalgroup.view', ['regionalgroup' => $regionalgroup])
                ->withSuccess('TODO: lang(`administration.regionalgroup.request.accepted`)');
        }

        // Now the harder part. FULLMEMBER request
        // A user can only join ONE regionalgroup as a fullmember
        // If the user is fullmember anywhere else we need to set that status to guest.
        // If the user is currently a guest in this regionalgroup, we only have to update to fullmember
        // If the user is not a member or guest anywhere, we can accept the request
        if ($regionalgroupRequest->type == 'member') {
            // 1. Check for membership status
            if ($regionalgroupRequest->account->isMemberOfAnyRegionalgroup()) {
                foreach (Regionalgroup::all() as $r) {
                    if ($r->id != $regionalgroupRequest->regionalgroup_id) {
                        $r->accounts()->updateExistingPivot($regionalgroupRequest->account->id, ['guest' => true]);
                    } else {
                        $r->accounts()->updateExistingPivot($regionalgroupRequest->account->id, ['guest' => false]);
                    }
                }
            }
            // 2. Is the account already guest?
            elseif ($regionalgroupRequest->account->isGuestOfRegionalgroup($regionalgroup)) {
                $regionalgroup->accounts()->updateExistingPivot($regionalgroupRequest->account->id, ['guest' => false]);
                // 3. Attach user to regionalgroup as fullmember
            } else {
                $regionalgroup->accounts()->attach($regionalgroupRequest->account, ['guest' => false, 'pilot' => true, 'controller' => true]);
            }

            MembershipLibrary::handleMembershipChange($regionalgroupRequest->account);
            // Delete the request, as we do not need it anymore
            $regionalgroupRequest->delete();

            return redirect()
                ->route('administration.regionalgroup.view', ['regionalgroup' => $regionalgroup])
                ->withSuccess('TODO: lang(`administration.regionalgroup.request.accepted`)');
        }

        // Delete the request, it is invalid!
        $regionalgroupRequest->delete();

        return redirect()
            ->route('administration.regionalgroup.view', ['regionalgroup' => $regionalgroup])
            ->withErrors('TODO: lang(`administration.regionalgroup.request.invalid`)');
    }

    /**
     * Delete regionalgroup request
     *
     * @param Request $request
     * @param Regionalgroup $regionalgroup
     * @param RegionalgroupRequest $regionalgroupRequest
     * @return RedirectResponse
     */
    public function delete(Request $request, Regionalgroup $regionalgroup, RegionalgroupRequest $regionalgroupRequest): RedirectResponse
    {
        $this->authorize('update', $regionalgroup);

        $regionalgroupRequest->delete();

        return redirect()
            ->route('administration.regionalgroup.view', ['regionalgroup' => $regionalgroup])
            ->withSuccess('TODO: lang(`administration.regionalgroup.request.denied`)');
    }
}
