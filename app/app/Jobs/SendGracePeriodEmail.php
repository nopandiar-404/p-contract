<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GracePeriodEmail;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Support\Facades\Log;

class SendGracePeriodEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300];

    public $contract;

    /**
     * Create a new job instance.
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Kirim ke admin email atau semua user dengan email Gmail
            $adminEmail = config('app.admin_email') ?: env('ADMIN_EMAIL');
            $recipients = [];

            if ($adminEmail) {
                // Kirim ke admin email jika dikonfigurasi
                $recipients[] = (object)['email' => $adminEmail, 'name' => 'Admin'];
            } else {
                // Kirim ke semua user dengan email Gmail
                $users = User::where('email', 'like', '%@gmail.com')->get();
                $recipients = $users->toArray();
            }

            if (empty($recipients)) {
                Log::warning('No recipients found for grace period email');
                return;
            }

            // Gunakan Mail facade untuk kirim langsung
            \Illuminate\Support\Facades\Mail::to($recipients)->send(new \App\Mail\GracePeriodMail($this->contract));

            Log::info("Grace period email sent for contract {$this->contract->contract_number}");
        } catch (\Exception $e) {
            Log::error("Failed to send grace period email for contract {$this->contract->contract_number}: " . $e->getMessage());
            throw $e;
        }
    }
}