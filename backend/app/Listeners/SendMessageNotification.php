<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        $message = $event->message;
        
        // Load sender relationship if not already loaded
        if (!$message->relationLoaded('sender')) {
            $message->load('sender');
        }

        // Create a notification for the recipient
        Notification::create([
            'user_id' => $message->recipient_id,
            'title' => 'New Message',
            'message' => "You have received a new message from {$message->sender->name}",
            'read' => false,
        ]);
    }
}
