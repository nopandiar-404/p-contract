<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Contract;

class GracePeriodMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contract;

    /**
     * Create a new message instance.
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $remainingDays = $this->contract->remaining_grace_period_days;

        return new Envelope(
            subject: "Peringatan: Kontrak {$this->contract->contract_number} Dalam Masa Tenggang {$remainingDays} Hari",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.grace_period',
            with: [
                'contract' => $this->contract,
                'remainingDays' => $this->contract->remaining_grace_period_days,
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