<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class WhatsAppFailureAlert extends Notification
{
    protected $to;
    protected $error;
    protected $contractId;

    public function __construct(string $to, string $error, $contractId = null)
    {
        $this->to = $to;
        $this->error = $error;
        $this->contractId = $contractId;
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $msg = "WhatsApp send failed for {$this->to}: {$this->error}";
        if ($this->contractId) {
            $msg .= " (contract_id: {$this->contractId})";
        }

        return (new SlackMessage)
            ->success()
            ->content($msg);
    }
}
