<?php

namespace App\Mail;

use App\Models\Membership\User;
use App\Notifications\BasicNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Stevebauman\Purify\Facades\Purify;

class BasicNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(public BasicNotification $notification)
    {
    }

    public function toUser(User $user): Mailable
    {
        $this->user = $user;
        return parent::to($user->email, $user->username);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), $this->notification->source_name . ' via VATSIM Germany'),
            replyTo: [new Address('support@vatger.de', 'VATSIM Germany Support')],
            subject: $this->notification->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mail-master1',
            with: [
                'title' => $this->notification->title,
                'source_name' => $this->notification->source_name,
                'message_text' => Purify::clean($this->notification->message),
                'link_text' => $this->notification->link_text,
                'link_url' => $this->notification->link_url,
                'valid_till' => $this->notification->valid_till,
                'delete_at' => $this->notification->delete_at,
                'user_id' => $this->user->id,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
