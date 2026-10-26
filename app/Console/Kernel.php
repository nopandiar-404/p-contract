<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Contract;
use App\Models\ContractNotification;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Registered commands
     */
    protected $commands = [
        \App\Console\Commands\SaveExportCommand::class,
        \App\Console\Commands\SendContractNotifications::class,
        \App\Console\Commands\WhatsAppHealthCheck::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notifications:contracts')->dailyAt('09:00');

        // Call ContractNotificationService to check and send WhatsApp reminders
        $schedule->call(function () {
            (new \App\Services\ContractNotificationService())->checkAndSendNotifications();
        })->dailyAt('08:30')->withoutOverlapping();

        // Update status kontrak setiap hari
        $schedule->call(function () {
            $today = Carbon::today();

            // Update kontrak aktif yang sudah lewat end_date ke masa tenggang
            Contract::active()
                ->whereDate('end_date', '<', $today)
                ->update(['status' => 'grace_period']);

            // Update kontrak masa tenggang yang sudah lewat
            Contract::inGracePeriod()
                ->whereDate('grace_period_end_date', '<', $today)
                ->update(['status' => 'expired']);

            // Kirim notifikasi untuk kontrak yang akan berakhir dalam 7 hari
            $reminderDate = $today->addDays(7);
            $contracts = Contract::active()
                ->whereDate('end_date', $reminderDate)
                ->get();

            foreach ($contracts as $contract) {
                ContractNotification::create([
                    'contract_id' => $contract->id,
                    'type' => 'reminder',
                    'message' => "Kontrak {$contract->contract_number} akan berakhir dalam 7 hari",
                    'sent_date' => $today
                ]);
            }

            // Notifikasi masa tenggang dimulai
            $graceStartContracts = Contract::active()
                ->whereDate('end_date', $today)
                ->get();

            foreach ($graceStartContracts as $contract) {
                ContractNotification::create([
                    'contract_id' => $contract->id,
                    'type' => 'grace_period_start',
                    'message' => "Kontrak {$contract->contract_number} memasuki masa tenggang",
                    'sent_date' => $today
                ]);
            }
        })->dailyAt('08:00');
    }
}
