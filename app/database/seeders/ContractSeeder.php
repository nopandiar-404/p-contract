<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting contract seeding...');

        // Find admin user by email, fallback to first admin
        $admin = User::where('email', 'admin@contract.app')->first();
        if (!$admin) {
            $admin = User::where('role', 'admin')->first();
        }

        if (!$admin) {
            $this->command->error('No admin user found. Please run UserSeeder first.');
            return;
        }

        // Create some dummy clients
        $clients = [];
        for ($i = 1; $i <= 3; $i++) {
            $clients[] = Client::firstOrCreate(
                ['email' => "vendor{$i}@example.com"],
                [
                    'name' => "Vendor {$i}",
                    'phone' => '0812' . rand(1000000, 9999999),
                    'address' => "Jl. Contoh No {$i}",
                    'pic_name' => "PIC Vendor {$i}",
                    'pic_phone' => '0812' . rand(1000000, 9999999),
                    'pic_email' => "pic{$i}@vendor.example.com",
                    'status' => 'active',
                    'created_by' => $admin->id,
                ]
            );
        }

        // Create dummy contracts assigned to admin
        $created = 0;
        foreach ($clients as $index => $client) {
            for ($j = 1; $j <= 2; $j++) {
                $start = now()->subMonths($j + $index);
                $end = (clone $start)->addMonths(6)->toDateString();

                $contract = Contract::firstOrCreate(
                    ['contract_number' => Contract::generateContractNumber()],
                    [
                        'contract_name' => "Kontrak Demo {$index}-{$j}",
                        'client_id' => $client->id,
                        'vendor_name' => $client->name,
                        'description' => 'Kontrak dummy untuk pengujian fitur.',
                        'dpp' => 10000000 + ($index * 500000) + ($j * 100000),
                        'start_date' => $start->toDateString(),
                        'end_date' => $end,
                        'status' => 'active',
                        'grace_period_days' => 30,
                        'created_by' => $admin->id,
                    ]
                );

                if ($contract->wasRecentlyCreated || $contract->exists) {
                    $created++;
                    $this->command->info("   ✓ Contract created: {$contract->contract_number} ({$contract->contract_name})");
                }
            }
        }

        $this->command->info("✅ Created {$created} dummy contracts for admin {$admin->email}");
    }
}
