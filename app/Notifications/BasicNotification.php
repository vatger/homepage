<?php

namespace App\Notifications;

use App\Mail\BasicNotificationMail;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

class BasicNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public string $title,
        public string $message, //supports markdown
        public string $source_name,
        public ?Carbon $valid_till = null,
        public ?Carbon $delete_at = null,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', BoardChannel::class, 'mail'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'source_name' => $this->source_name,
            'valid_till' => $this->valid_till?->toDateTimeString(),
            'delete_at' => $this->delete_at?->toDateTimeString(),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toBoard(object $notifiable): array
    {
        return [
            'message' => strip_tags($this->title) . ': {link}',
            'link_text' => 'mehr Details',
            'link_url' => 'vatger.de', //route("") to notifications
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        $url = url('/');
        $mail = new BasicNotificationMail($this);
        if (!$notifiable instanceof User) {
            return null;
        }
        $user = User::query()->find($notifiable->id);
        if (empty($user)) {
            return null;
        }
        return $mail->toUser($user);
    }
}
