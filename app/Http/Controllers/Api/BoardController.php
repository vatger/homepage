<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Libraries\XenForoLibrary;
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
        $post_id = 44;
        $validated = $request->validate([
            'text_data' => ['required', 'string'],
            'table_data' => ['array'],
            'table_data.*.trainee' => ['required', 'string'],
            'table_data.*.date' => ['required', 'string'],
            'table_data.*.position' => ['required', 'string'],
        ]);
        $message = '[TABLE width="100%"]
                    [TR]
                    [th]Trainee[/th]
                    [th]Position[/th]
                    [th]Datum[/th]
                    [/TR]';
        foreach ($validated['table_data'] as $table_data) {
            $message .= '[TR]';
            $message .= '[td width="33.3333%"]'.$table_data->trainee.'[/td]';
            $message .= '[td width="33.3333%"]'.$table_data->position.'[/td]';
            $message .= '[td width="33.3333%"]'.$table_data->date.'[/td]';
            $message .= '[/TR]';
        }
        $message .= '[/TABLE]';
        $message .= $validated['text_data'];

        return XenForoLibrary::updatePost($post_id, $message);
    }
}
