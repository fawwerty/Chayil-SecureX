<?php 
namespace App\Mail;
 use App\Models\Incident;
  use Illuminate\Bus\Queueable; 
  use Illuminate\Mail\Mailable;
  use Illuminate\Queue\SerializesModels; 
  
  class IncidentNotificationMail extends Mailable
   { 
      use Queueable, SerializesModels; 
     public $incident;

     public function __construct(Incident $incident)
      { 
        $this->incident = $incident; } public function build()
         {
             return $this->subject('Incident Notification') ->view('emails.incident-notification') ->with(['incident' => $this->incident]);
            
            } }