<?php

namespace App\Mail;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IncidentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;

    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Incident Report: ' . $this->incident->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.incident-notification',
            with: [
                'incident' => $this->incident,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}