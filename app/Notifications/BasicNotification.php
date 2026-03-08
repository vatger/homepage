<?php

namespace App\Notifications;

use App\Mail\BasicNotificationMail;
use App\Models\Membership\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Stevebauman\Purify\Facades\Purify;

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
        public string $message, // supports markdown
        public string $source_name,
        public ?string $link_text = null,
        public ?string $link_url = null,
        public ?Carbon $valid_till = null,
        public ?Carbon $delete_at = null,
        public ?array $via = null
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        if (empty($this->via)) {
            return ['database', BoardChannel::class, 'mail'];
        }
        $res = ['database'];
        if (array_any($this->via, fn ($s) => strcasecmp($s, 'board.ping') == 0)) {
            $res[] = BoardChannel::class;
        }
        if (array_any($this->via, fn ($s) => strcasecmp($s, 'board.pn') == 0)) {
            $res[] = BoardPNChannel::class;
        }
        if (array_any($this->via, fn ($s) => strcasecmp($s, 'mail') == 0)) {
            $res[] = 'mail';
        }

        return $res;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => Purify::clean($this->message),
            'source_name' => $this->source_name,
            'link_text' => $this->link_text,
            'link_url' => $this->link_url,
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
            'message' => strip_tags($this->title).$this->link_text ? ': {link}' : '',
            'link_text' => $this->link_text,
            'link_url' => $this->link_url,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): ?Mailable
    {
        $mail = new BasicNotificationMail($this);
        if (! $notifiable instanceof User) {
            return null;
        }
        $user = User::query()->find($notifiable->id);
        if (empty($user)) {
            return null;
        }

        return $mail->toUser($user);
    }
}
