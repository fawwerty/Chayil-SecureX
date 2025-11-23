<?php

namespace App\Listeners;

use App\Events\IncidentCreated;
use App\Mail\IncidentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable;

class SendIncidentNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle( $event): void
    {
        $incident = $event->incident->load('reporter');
        
        $adminEmails = config('mail.admin_emails', ['admin@example.com']);
        
        foreach ($adminEmails as $email) {
            Mail::to($email)->queue(new IncidentNotification($incident));
        }
        
        if ($incident->reporter && $incident->reporter->email) {
            Mail::to($incident->reporter->email)
                ->queue(new IncidentNotification($incident));
        }
    }
}