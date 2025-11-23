<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Incident;

class IncidentReported
{
    use Dispatchable, SerializesModels;

    public $incident;

    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
    }
}
