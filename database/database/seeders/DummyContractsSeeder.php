<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DummyContractsSeeder extends Seeder
{
    public function run()
    {
        // Load a list of non-admin users to assign as creators. If none exist, create a fallback user.
        $users = User::where('role', '!=', 'admin')->get();
        if ($users->isEmpty()) {
            $fallback = User::first();
            if (!$fallback) {
                $fallback = User::create([
                    'name' => 'Seeder Admin',
                    'email' => 'seeder@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                ]);
            }
            $users = collect([$fallback]);
        }

        $userCount = $users->count();
        $userIndex = 0; // round-robin index

        // Ensure we have at least five clients to attach contracts to
        for ($i = 1; $i <= 5; $i++) {
            Client::firstOrCreate(
                ['email' => "client{$i}@example.com"],
                [
                    'name' => 'Dummy Client ' . $i,
                    'phone' => '081200000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );
        }

        $clients = Client::take(5)->get();

        $now = Carbon::now();

        foreach ($clients as $client) {
            // Create a few contracts that ended last year (3 samples)
            $pastOffsets = [-12, -9, -6];
            foreach ($pastOffsets as $monthsAgo) {
                $end = (clone $now)->addMonths($monthsAgo)->endOfMonth();
                $start = (clone $end)->subMonths(rand(3, 12))->startOfMonth();

                Contract::create([
                    'contract_number' => null,
                    'contract_name' => 'Dummy Past Contract ' . Str::upper(Str::random(6)),
                    'client_id' => $client->id,
                    'description' => 'Generated past contract for testing',
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                    'status' => $end->lt($now) ? 'expired' : 'active',
                    'grace_period_days' => 30,
                    'created_by' => $users[$userIndex % $userCount]->id
                ]);
                $userIndex++;
            }

            // Create contracts for the next six months (1 per month)
            for ($m = 1; $m <= 6; $m++) {
                $end = (clone $now)->addMonths($m)->endOfMonth();
                $start = (clone $end)->subMonths(rand(1, 6))->startOfMonth();

                Contract::create([
                    'contract_number' => null,
                    'contract_name' => 'Dummy Future Contract ' . Str::upper(Str::random(6)),
                    'client_id' => $client->id,
                    'description' => 'Generated future contract for testing',
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                    'status' => $end->lt($now) ? 'expired' : 'active',
                    'grace_period_days' => 30,
                    'created_by' => $users[$userIndex % $userCount]->id
                ]);
                $userIndex++;
            }
        }
    }
}
