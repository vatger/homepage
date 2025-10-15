<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Models\Membership\User;
use App\Notifications\BasicNotification;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * User Membership
     *
     * Show some basic info about the membership.
     */
    #[ApiPathfinder('user.details')]
    public function details(User $cid, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorizeApiRequest('user.details');

        $user = $cid;

        return response()->json(
            (object) [
                'vatsim_id' => $user->id,
                'fir_name' => $user->fir,
                'atc_rating' => $user->vatsimDetails?->rating_atc,
                'pilot_rating' => $user->vatsimDetails?->rating_pilot,
                'teams' => $user
                    ->teams()
                    ->map(fn ($team) => $team->name)
                    ->values()
                    ->toArray(),
            ]);
    }

    /**
     * User Notification
     *
     * Send a notification to the user via board,mail,homepage,...
     */
    #[ApiPathfinder('user.send_notification')]
    public function send_notification(User $cid, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorizeApiRequest('user.send_notification');

        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'source_name' => 'required|string',
            'link_text' => 'nullable|string',
            'link_url' => 'nullable|string',
            'via' => 'nullable|string',
        ]);

        $title = $request->input('title');
        $message = $request->input('message');
        $source_name = $request->input('source_name');
        $link_text = $request->input('link_text');
        $link_url = $request->input('link_url');
        $via = empty($request->input('via')) ? null : explode(',', $request->input('via'));

        $notification = new BasicNotification($title, $message, $source_name, $link_text, $link_url, null, null, $via);
        $cid->notify($notification);

        \Log::info(json_encode($notification));

        return response()->json(['status' => 'Notification sent successfully']);
    }
}
