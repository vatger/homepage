<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function viewProfile(): Factory|View|Application
    {
        return view('pages.membership')->with(['user' => $this->user()]);
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getNotificationsPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        return $this->user()
            ?->notifications()
            ?->paginate(10);
    }
}
