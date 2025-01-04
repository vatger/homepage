<?php

namespace App\Http\Controllers\Administration\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\ShortLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShortLinkController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $links = ShortLink::query()
            ->where('active_until', '>', $now)
            ->orWhere('active_until', null)
            ->paginate(15);

        return view('administration.content.url.index')->with(['links' => $links]);
    }

    public function getUrlsPaginated(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }
        // TODO AUTH

        $now = Carbon::now();

        return ShortLink::query()
            ->where('active_until', '>', $now)
            ->orWhere('active_until', null)
            ->paginate(15);
    }

    public function getUrlSearch(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }

        $now = Carbon::now();

        return ShortLink::query()
            ->where(function ($query) use ($request) {
                $query
                    ->where('shortLink', 'LIKE', '%'.$request->get('search_param').'%')
                    ->orWhere('link', 'LIKE', '%'.$request->get('search_param').'%');
            })
            ->where(function ($query) use ($now) {
                $query->where('active_until', '>', $now)->orWhere('active_until', null);
            })
            ->get();
    }

    public function createShortLink(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }
        // TODO: Auth (on all funcs)

        $request->validate([
            'shortLink' => 'required|max:70',
            'link' => 'required',
            'active' => 'required',
            'active-until-toggle' => 'required',
            'active-until' => 'date_format:d.m.Y',
        ]);

        $date = Carbon::parse($request->post('active-until'));

        $potConflicts = ShortLink::query()
            ->where('shortLink', $request->post('shortLink'))
            ->get();
        foreach ($potConflicts as $conflict) {
            if ($conflict->active && (Carbon::parse($conflict->active_until)->isFuture() || ! $conflict->active_until)) {
                return response()->json(['error' => 'Diese gekürzte URL wird bereits verwendet und kann daher nicht erstellt werden.'], 404);
            } else {
                $conflict->delete();
            }
        }

        return ShortLink::query()->create([
            'shortLink' => $request->post('shortLink'),
            'link' => $request->post('link'),
            'creator' => Auth::user()->id,
            'active' => $request->post('active'),
            'active_until' => $request->post('active') == '1' && $request->post('active-until-toggle') == '1' ? $date : null,
        ]);
    }

    public function removeShortLink(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }
        // TODO: Auth (on all funcs)

        $sl = ShortLink::query()
            ->where('id', $request->post('link_id'))
            ->firstOrFail();

        $sl->delete();

        return response()->json(['success' => 'Die URL wurde erfolgreich gelöscht']);
    }

    public function toggleActivity(Request $request)
    {
        if (! $request->ajax()) {
            abort(403, 'Method not supported');
        }
        // TODO: Auth

        $sl = ShortLink::query()
            ->where('id', $request->get('link_id'))
            ->firstOrFail();

        $sl->update([
            'active' => ! $sl->active,
        ]);

        return $sl;
    }

    /**
     * Only function that is publically available,
     * shows the result of the link (ie. a redirect)
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function viewLink(Request $request, $shortLink)
    {
        $redir = ShortLink::query()
            ->where('shortLink', $shortLink)
            ->first();
        $now = Carbon::now();

        if ($redir && $redir->active) {
            $lnk = $redir->link;
            if ($lnk != null && ! str_contains(strtolower($lnk), 'http')) {
                $lnk = 'https://'.$lnk;
            }

            if ($redir->active_until && $now > $redir->active_until) {
                return view('administration.content.url.notfound')->with(['req' => $shortLink, 'exp' => true]);
            } else {
                return redirect($lnk)->with(['expired' => $now > $redir->active_until]);
            }
        } else {
            return view('administration.content.url.notfound')->with(['req' => $shortLink, 'exp' => false]);
        }
    }
}
