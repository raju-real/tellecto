<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $mail_data = [];

    /**
     * Create a new message instance.
     */
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mail_data['subject']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Retrieve the view name from mail_data or use a default view
        $viewName = $this->mail_data['view_file'] ?? 'mail.common_mail_template';
        return new Content(
            view: $viewName,
            with: ['mail_data' => $this->mail_data],
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (!empty($this->mail_data['attachment_path'])) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($this->mail_data['attachment_path'])
                    ->as($this->mail_data['attachment_name'] ?? 'Attachment.pdf') // Customize name if provided
                    ->withMime($this->mail_data['attachment_mime'] ?? 'application/octet-stream'), // Default MIME type
            ];
        }

        return [];
    }

}
