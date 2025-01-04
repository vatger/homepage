<?php

namespace App\OpenApi\Controllers;

use App\Models\Membership\User;
use App\Notifications\BasicNotification;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\Parameters\SendNotificationsParameters;
use App\OpenApi\Responses\ListUsersResponse;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class UserController extends ApiController
{
    /**
     * User Membership
     *
     * Show some basic info about the membership.
     */
    #[OpenApi\Operation]
    #[OpenApi\Response(ListUsersResponse::class)]
    public function membership(User $cid) {}

    /**
     * User Notification
     *
     * Send a notification to the user via board,mail,homepage,...
     */
    #[OpenApi\Operation]
    #[ApiPathfinder('user.send_notification')]
    #[OpenApi\Parameters(SendNotificationsParameters::class)]
    public function send_notification(User $cid, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorizeApiRequest('user.send_notification');

        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'source_name' => 'required|string',
            'link_text' => 'nullable|string',
            'link_url' => 'nullable|url',
        ]);

        $title = $request->input('title');
        $message = $request->input('message');
        $source_name = $request->input('source_name');
        $link_text = $request->input('link_text');
        $link_url = $request->input('link_url');

        $notification = new BasicNotification($title, $message, $source_name, $link_text, $link_url);
        $cid->notify($notification);

        return response()->json(['status' => 'Notification sent successfully']);
    }
}
