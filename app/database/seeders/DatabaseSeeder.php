<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PelindoSampleContractsSeeder::class,
                // tambahkan seeder lainnya
        ]);

        // Pastikan ada beberapa akun user sehingga kontrak dapat didistribusikan ke akun berbeda
        $defaultUsers = [
            ['email' => 'superadmin@contract.app', 'name' => 'Super Admin', 'password' => Hash::make('Admin123!'), 'role' => 'admin'],
            ['email' => 'user1@contract.app', 'name' => 'User One', 'password' => Hash::make('User123!'), 'role' => 'user'],
            ['email' => 'user2@contract.app', 'name' => 'User Two', 'password' => Hash::make('User123!'), 'role' => 'user'],
            ['email' => 'user3@contract.app', 'name' => 'User Three', 'password' => Hash::make('User123!'), 'role' => 'user'],
        ];

        foreach ($defaultUsers as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                $u
            );
        }

        $users = User::whereIn('email', array_column($defaultUsers, 'email'))->get();
        $userIds = $users->pluck('id')->toArray();
        // Counter untuk distribusi round-robin
        $nextUser = 0;


        // Buat beberapa klien
        $clients = Client::factory()->count(10)->create();

        // Contoh klien pelabuhan untuk data kontrak pekerjaan pelabuhan
        $portClientSeedData = [
            [
                'name' => 'PT Pelabuhan Nusantara',
                'email' => 'port1@example.com',
                'phone' => '081234567890',
                'status' => 'active',
            ],
            [
                'name' => 'CV Perkasa Dermaga',
                'email' => 'port2@example.com',
                'phone' => '081234567891',
                'status' => 'active',
            ],
            [
                'name' => 'PT Layanan Pelabuhan',
                'email' => 'port3@example.com',
                'phone' => '081234567892',
                'status' => 'active',
            ],
        ];

        foreach ($portClientSeedData as $portClientData) {
            Client::firstOrCreate(
                ['email' => $portClientData['email']],
                $portClientData
            );
        }

        $portClients = Client::whereIn('email', ['port1@example.com', 'port2@example.com', 'port3@example.com'])->get();

        $samplePortContracts = [
            [
                'contract_number' => 'CTR-PLB-001',
                'contract_name' => 'Kontrak Bongkar Muat Petikemas Dermaga 2',
                'description' => 'Pekerjaan bongkar muat dan penanganan logistik container di dermaga utama.',
                'dpp' => 235000000,
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(2)->startOfMonth()->toDateString(),
                'end_date' => Carbon::now()->addMonths(7)->endOfMonth()->toDateString(),
                'grace_period_days' => 30,
            ],
            [
                'contract_number' => 'CTR-PLB-002',
                'contract_name' => 'Kontrak Perawatan Dermaga Utama',
                'description' => 'Perawatan berkala dan perbaikan struktural pada dermaga utama pelabuhan.',
                'dpp' => 172500000,
                'status' => 'grace_period',
                'start_date' => Carbon::now()->subMonths(8)->startOfMonth()->toDateString(),
                'end_date' => Carbon::now()->subDays(10)->toDateString(),
                'grace_period_days' => 30,
            ],
            [
                'contract_number' => 'CTR-PLB-003',
                'contract_name' => 'Kontrak Pengelolaan Terminal Curah',
                'description' => 'Pengelolaan operasional dan pengawasan arus barang terminal curah.',
                'dpp' => 298000000,
                'status' => 'expired',
                'start_date' => Carbon::now()->subMonths(14)->startOfMonth()->toDateString(),
                'end_date' => Carbon::now()->subMonths(5)->endOfMonth()->toDateString(),
                'grace_period_days' => 30,
            ],
        ];

        foreach ($portClients as $index => $portClient) {
            $contractData = $samplePortContracts[$index];
            Contract::firstOrCreate(
                ['contract_number' => $contractData['contract_number']],
                array_merge($contractData, [
                    'client_id' => $portClient->id,
                    'created_by' => $userIds[$nextUser++ % count($userIds)],
                ])
            );
        }

        // Buat kontrak dengan berbagai status
        foreach ($clients as $client) {
            Contract::factory()->create([
                'client_id' => $client->id,
                'created_by' => $userIds[$nextUser++ % count($userIds)],
                'status' => 'active',
                'end_date' => Carbon::now()->addDays(rand(1, 60))
            ]);

            Contract::factory()->create([
                'client_id' => $client->id,
                'created_by' => $userIds[$nextUser++ % count($userIds)],
                'status' => 'grace_period',
                'end_date' => Carbon::now()->subDays(rand(1, 20))
            ]);
        }
    }
}