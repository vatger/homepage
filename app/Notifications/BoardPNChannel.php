<?php

namespace App\Notifications;

use App\Libraries\XenForoLibrary;
use App\Models\Membership\User;
use Illuminate\Notifications\Notification;

class BoardPNChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        // Send notification to the $notifiable instance...

        if (! $notifiable instanceof User) {
            return;
        }
        $user = User::find($notifiable->id);
        if (empty($user)) {
            return;
        }
        $data = method_exists($notification, 'toArray') ? $notification->toArray($notifiable) : null;
        if (empty($data)) {
            return;
        }

        // call the board library to generate a new notification :)
        if (! array_key_exists('message', $data)) {
            return;
        }
        $message = $data['message'];
        $title = $data['title'];

        XenForoLibrary::sendAccountNotification($user, $title, $message);
    }
}
