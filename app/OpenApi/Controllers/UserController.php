<?php

namespace App\OpenApi\Controllers;

use App\Models\Membership\User\User;
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
    public function membership(User $cid)
    {
    }

    /**
     * User Notification
     *
     * Send a notification to the user via board,mail,homepage,...
     */
    #[OpenApi\Operation]
    public function send_notification(User $cid, Request $request)
    {
    }
}
