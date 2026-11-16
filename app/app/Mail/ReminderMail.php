<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $bodyHtml;

    public function __construct(string $subjectLine, string $bodyHtml)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyHtml = $bodyHtml;
        $this->subject($subjectLine);
    }

    public function build()
    {
        return $this->view('emails.reminder')->with(['body' => $this->bodyHtml]);
    }
}
