<?php

namespace App\Mail;

use App\Models\Membership\User\User;
use App\Notifications\BasicNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BasicNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public BasicNotification $notification)
    {
    }

    public function toUser(User $user): Mailable
    {
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
            view: 'emails.mail-master',
            with: [
                'title' => $this->notification->title,
                'source_name' => $this->notification->source_name,
                'message_text' => $this->notification->message,
                'link_text' => $this->notification->link_text,
                'link_url' => $this->notification->link_url,
                'valid_till' => $this->notification->valid_till,
                'delete_at' => $this->notification->delete_at,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
