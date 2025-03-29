<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Models\Membership\User;
use Illuminate\Http\Request;

class BoardController extends ApiController
{
    /**
     * Create a forum user
     */
    #[ApiPathfinder('board.create')]
    public function create(Request $request): bool
    {
        $this->authorizeApiRequest('board.create');
        $validated = $request->validate([
            'vatsim_id' => ['required', 'integer'],
            'forum_id' => ['required', 'integer'],
        ]);

        $vatsim_id = $validated['vatsim_id'];
        $forum_id = $validated['forum_id'];

        $u = User::find($vatsim_id);

        if (empty($u)) {
            abort(404, "User $vatsim_id not found");
        }
        if ($u->settings->forum_id) {
            abort(400, "User $vatsim_id already has an account");
        }

        $u->settings->forum_id = $forum_id;

        $u->settings->save();

        return true;
    }
}
