<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use Illuminate\Support\Str;

class FillVendorDataForGracePeriodSeeder extends Seeder
{
    public function run()
    {
        $updated = 0;

        $contracts = Contract::with('client')
            ->where('status', 'grace_period')
            ->get();

        foreach ($contracts as $contract) {
            $c = $contract->client;
            if (!$c) continue;

            $changed = false;

            if (empty($c->pic_name)) {
                $c->pic_name = 'PIC ' . explode(' ', trim($c->name))[0];
                $changed = true;
            }

            if (empty($c->email)) {
                $c->email = 'contact@' . Str::slug($c->name) . '.example';
                $changed = true;
            }

            if (empty($c->phone)) {
                $c->phone = '081' . rand(1000000, 9999999);
                $changed = true;
            }

            if ($changed) {
                $c->save();
                $updated++;
                $this->command->info('Updated client: ' . $c->id);
            }
        }

        $this->command->info('Total clients updated: ' . $updated);
    }
}
