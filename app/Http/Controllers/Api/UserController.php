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
    public function membership(User $cid) {}

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
        ]);

        $title = $request->input('title');
        $message = $request->input('message');
        $source_name = $request->input('source_name');
        $link_text = $request->input('link_text');
        $link_url = $request->input('link_url');

        $notification = new BasicNotification($title, $message, $source_name, $link_text, $link_url);
        $cid->notify($notification);

        \Log::info(json_encode($notification));

        return response()->json(['status' => 'Notification sent successfully']);
    }
}
