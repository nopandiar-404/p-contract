<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\GmailService;
use App\Models\ContractNotification;

class SendGmailMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $to;
    public $subject;
    public $htmlBody;
    public $contractId;
    public $notificationId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $to, string $subject, string $htmlBody, $contractId = null, $notificationId = null)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->htmlBody = $htmlBody;
        $this->contractId = $contractId;
        $this->notificationId = $notificationId;
        $this->onQueue('mail');
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $sent = GmailService::send($this->to, $this->subject, $this->htmlBody);

            if ($sent && $this->notificationId) {
                try {
                    $notif = ContractNotification::find($this->notificationId);
                    if ($notif) {
                        $notif->update(['sent_date' => now()]);
                    }
                } catch (\Exception $e) {
                    Log::warning('SendGmailMessage: failed to update notification sent_date: ' . $e->getMessage());
                }
            }

            if (!$sent) {
                Log::error('SendGmailMessage: failed to send email', ['to' => $this->to]);
            }
        } catch (\Exception $e) {
            Log::error('SendGmailMessage: exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            // Let job fail/retry according to queue settings
            throw $e;
        }
    }
}
