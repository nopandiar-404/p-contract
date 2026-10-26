<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ContractNotificationService
{
    private $notificationDays = [60, 30, 15, 7, 3, 1];

    public function checkAndSendNotifications()
    {
        $today = Carbon::today();
        $results = [
            'expiring' => 0,
            'grace_period' => 0,
            'expired' => 0,
            'errors' => []
        ];

        try {
            // 1. Cek kontrak yang akan berakhir
            foreach ($this->notificationDays as $daysBefore) {
                $targetDate = $today->copy()->addDays($daysBefore);

                $contracts = Contract::where('status', 'active')
                    ->whereDate('end_date', $targetDate)
                    ->get();

                foreach ($contracts as $contract) {
                    $notification = $this->createExpiryNotification($contract, $daysBefore);
                    $results['expiring']++;
                }
            }

            // 2. Cek kontrak dalam masa tenggang
            // Note: `remaining_grace_period_days` is an accessor, not a DB column,
            // so fetch grace_period contracts and filter in PHP to avoid SQL errors.
            $graceContracts = Contract::where('status', 'grace_period')->get();

            foreach ($graceContracts as $contract) {
                try {
                    $remainingDays = $contract->remaining_grace_period_days;
                    if ($remainingDays !== null) {
                        if ($remainingDays <= 3) {
                            $this->createGracePeriodNotification($contract);
                            $results['grace_period']++;
                        }
                        if ($remainingDays >= 14 && $remainingDays <= 16) {
                            $this->sendGracePeriodEmail($contract);
                            $results['grace_period_email'] = ($results['grace_period_email'] ?? 0) + 1;
                        }
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = 'Grace filter error for contract ' . $contract->id . ': ' . $e->getMessage();
                }
            }

            // 3. Cek kontrak yang expired hari ini
            $expiredContracts = Contract::where('status', 'expired')
                ->whereDate('end_date', $today)
                ->get();

            foreach ($expiredContracts as $contract) {
                $this->createExpiredNotification($contract);
                $results['expired']++;
            }
        } catch (\Exception $e) {
            $results['errors'][] = $e->getMessage();
        }

        return $results;
    }

    private function createExpiryNotification(Contract $contract, $daysBefore)
    {
        // Cek duplikasi dengan cara sederhana
        $existing = ContractNotification::where('contract_id', $contract->id)
            ->where('type', ContractNotification::TYPE_EXPIRY)
            ->whereDate('sent_date', Carbon::today())
            ->first();

        if ($existing) {
            return $existing;
        }

        return ContractNotification::createExpiryNotification($contract, $daysBefore);
    }

    private function createGracePeriodNotification(Contract $contract)
    {
        $existing = ContractNotification::where('contract_id', $contract->id)
            ->where('type', ContractNotification::TYPE_GRACE_PERIOD)
            ->whereDate('sent_date', Carbon::today())
            ->first();

        if (!$existing) {
            ContractNotification::createGracePeriodNotification($contract);
        }
    }

    private function createExpiredNotification(Contract $contract)
    {
        ContractNotification::createStatusChangeNotification(
            $contract,
            'active',
            'expired'
        );
    }

    private function sendGracePeriodEmail(Contract $contract)
    {
        // Cek apakah sudah dikirim hari ini untuk menghindari duplikasi
        $cacheKey = "grace_period_email_{$contract->id}_" . Carbon::today()->format('Y-m-d');
        if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return;
        }

        // Dispatch job untuk kirim email
        \App\Jobs\SendGracePeriodEmail::dispatch($contract);

        // Cache selama 24 jam untuk menghindari pengiriman ulang
        \Illuminate\Support\Facades\Cache::put($cacheKey, true, 1440);
    }
}
