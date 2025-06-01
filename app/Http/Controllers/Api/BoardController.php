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

    /**
     * Update an existing forum post
     */
    #[ApiPathfinder('board.update_post')]
    public function update_post(int $post_id, Request $request): bool
    {
        $this->authorizeApiRequest('board.update_post');
        $validated = $request->validate([
            'text_data' => ['required', 'string'],
        ]);

        return true;
    }

    /**
     * Update the existing cpt forum post
     */
    #[ApiPathfinder('board.update_cpt_post')]
    public function update_cpt_post(Request $request): bool
    {
        $this->authorizeApiRequest('board.update_post');
        $post_id = -1;
        $validated = $request->validate([
            'text_data' => ['required', 'string'],
            'table_data' => ['array'],
            'table_data.*.trainee' => ['string'],
            'table_data.*.date' => ['string'],
            'table_data.*.position' => ['string'],
        ]);

        return true;
    }
}
