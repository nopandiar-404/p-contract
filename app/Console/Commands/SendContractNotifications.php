<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ContractNotificationService;

class SendContractNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:contracts {--now : Run immediately and output summary}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check contracts and dispatch WhatsApp reminder notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking contracts and sending notifications...');

        $service = new ContractNotificationService();

        $results = $service->checkAndSendNotifications();

        $this->info('Results:');
        $this->line(' - Expiring processed: ' . ($results['expiring'] ?? 0));
        $this->line(' - Grace period processed: ' . ($results['grace_period'] ?? 0));
        $this->line(' - Grace period emails sent: ' . ($results['grace_period_email'] ?? 0));
        $this->line(' - Expired processed: ' . ($results['expired'] ?? 0));

        if (!empty($results['errors'])) {
            $this->error('Errors:');
            foreach ($results['errors'] as $err) {
                $this->line('   - ' . $err);
            }
        }

        $this->info('Done. WhatsApp jobs are dispatched to the `whatsapp` queue (if configured).');

        return 0;
    }
}
