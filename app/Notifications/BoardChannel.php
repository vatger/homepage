<?php

namespace App\Notifications;

use App\Libraries\XenForoLibrary;
use App\Models\Membership\User;
use Illuminate\Notifications\Notification;

class BoardChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        // Send notification to the $notifiable instance...

        if (!$notifiable instanceof User) {
            return;
        }
        $user = User::find($notifiable->id);
        if (empty($user)) {
            return;
        }
        $data = (method_exists($notification, 'toBoard')
            ? $notification->toBoard($notifiable)
            : method_exists($notification, 'toArray'))
            ? $notification->toArray($notifiable)
            : null;
        if (empty($data)) {
            return;
        }

        // call the board library to generate a new notification :)
        if (!array_key_exists('message', $data)) {
            return;
        }
        $message = $data['message'];
        $link_text = '';
        $link_url = '';
        if (array_key_exists('link_text', $data)) {
            $link_text = $data['link_text'];
        }
        if (array_key_exists('link_url', $data)) {
            $link_url = $data['link_url'];
        }

        XenForoLibrary::sendForumAlert($user, $message, $link_url, $link_text);
    }
}
