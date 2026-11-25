<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ContractNotificationService
{
    private $notificationDays = [60, 30, 15, 7, 3, 1];

    private function updateContractStatuses(Carbon $today): void
    {
        Contract::active()
            ->whereDate('end_date', '<=', $today)
            ->update([
                'status' => 'grace_period',
                'updated_at' => now(),
            ]);

        Contract::inGracePeriod()
            ->get()
            ->each(function (Contract $contract) use ($today) {
                $gracePeriodEnd = $contract->grace_period_end_date;
                if ($gracePeriodEnd && $gracePeriodEnd->lte($today)) {
                    $contract->update([
                        'status' => 'expired',
                        'updated_at' => now(),
                    ]);
                }
            });
    }

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
            $this->updateContractStatuses($today);

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
                        if (in_array($remainingDays, [30, 15, 7, 3, 1, 0], true)) {
                            $this->createGracePeriodNotification($contract, $remainingDays);
                            $results['grace_period']++;
                        }
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = 'Grace filter error for contract ' . $contract->id . ': ' . $e->getMessage();
                }
            }

            // 3. Buat satu notifikasi saat masa tenggang berakhir
            $expiredContracts = Contract::where('status', 'expired')
                ->whereDate('updated_at', $today)
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

    private function createGracePeriodNotification(Contract $contract, ?int $remainingDays = null)
    {
        $existing = ContractNotification::where('contract_id', $contract->id)
            ->where('type', ContractNotification::TYPE_GRACE_PERIOD)
            ->whereDate('sent_date', Carbon::today())
            ->first();

        if (!$existing) {
            $notification = ContractNotification::createGracePeriodNotification($contract);
            if ($remainingDays !== null) {
                $data = $notification->data ?? [];
                $data['remaining_grace_days'] = $remainingDays;
                $notification->update(['data' => $data]);
            }
        }
    }

    private function createExpiredNotification(Contract $contract)
    {
        $existing = ContractNotification::where('contract_id', $contract->id)
            ->whereIn('type', ['contract_expired', 'status_change'])
            ->whereDate('sent_date', Carbon::today())
            ->first();

        if ($existing) {
            return $existing;
        }

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
