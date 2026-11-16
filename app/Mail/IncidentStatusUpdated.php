<?php

namespace App\Mail;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IncidentStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;
    public $oldStatus;
    public $newStatus;

    public function __construct(Incident $incident, $oldStatus, $newStatus)
    {
        $this->incident = $incident;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        return $this->subject('Incident Status Updated')
            ->view('emails.incident_status_updated');
    }
}
