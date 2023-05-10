<?php

namespace App\Notifications;

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
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', BoardChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
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
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBoard($notifiable)
    {
        return [
            'message' => strip_tags($this->title) . ': {link}',
            'link_text' => 'mehr Details',
            'link_url' => 'vatger.de', //route("") to notifications
        ];
    }
}
