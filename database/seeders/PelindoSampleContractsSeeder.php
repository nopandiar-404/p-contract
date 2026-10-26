<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelindoSampleContractsSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'local-test@example.com')->first()
            ?? User::where('role', 'admin')->orderBy('id')->first()
            ?? User::orderBy('id')->first();

        if (!$owner) {
            $owner = User::create([
                'name' => 'Administrator Demo',
                'email' => 'admin-demo-pelindo@example.test',
                'password' => Hash::make(Str::random(40)),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        $client = Client::firstOrCreate(
            ['email' => 'pelindo.demo@example.test'],
            [
                'name' => 'PT Pelabuhan Indonesia (Persero) - Cabang Tanjung Priok',
                'phone' => '0214301080',
                'address' => 'Jl. Raya Pelabuhan No. 1, Tanjung Priok, Jakarta Utara',
                'pic_name' => 'Koordinator Pengadaan Pelabuhan',
                'pic_phone' => '0214301081',
                'pic_email' => 'pengadaan.pelindo@example.test',
                'status' => 'active',
                'created_by' => $owner->id,
            ]
        );

        $contracts = [
            [
                'contract_number' => 'PLD-UTL-2026-001',
                'contract_name' => 'Pekerjaan Pemeliharaan Jaringan Utilitas Dermaga',
                'contract_type' => 'contract_jasa',
                'description' => 'Pemeliharaan jaringan listrik, penerangan, dan distribusi utilitas di area dermaga Tanjung Priok.',
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->addMonths(12)->endOfMonth()->toDateString(),
                'boq_items' => [
                    ['description' => 'Survei dan pemetaan jaringan utilitas dermaga', 'unit' => 'paket', 'quantity' => 1, 'unit_price' => 25000000],
                    ['description' => 'Penggantian kabel distribusi listrik bawah tanah', 'unit' => 'meter', 'quantity' => 450, 'unit_price' => 185000],
                    ['description' => 'Pengadaan dan pemasangan panel distribusi listrik', 'unit' => 'unit', 'quantity' => 2, 'unit_price' => 18500000],
                    ['description' => 'Pengadaan dan pemasangan lampu LED area dermaga', 'unit' => 'unit', 'quantity' => 36, 'unit_price' => 2750000],
                    ['description' => 'Pengujian, commissioning, dan dokumentasi pekerjaan', 'unit' => 'paket', 'quantity' => 1, 'unit_price' => 42000000],
                ],
            ],
            [
                'contract_number' => 'PLD-SI-2026-002',
                'contract_name' => 'Pekerjaan Pengembangan Sistem Informasi Operasional Pelabuhan',
                'contract_type' => 'contract_jasa',
                'description' => 'Pengembangan sistem informasi layanan kapal dan barang, integrasi data operasional, serta pelatihan pengguna.',
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->addMonths(10)->endOfMonth()->toDateString(),
                'boq_items' => [
                    ['description' => 'Analisis kebutuhan dan perancangan sistem informasi', 'unit' => 'paket', 'quantity' => 1, 'unit_price' => 32000000],
                    ['description' => 'Pengembangan modul layanan kapal dan barang', 'unit' => 'paket', 'quantity' => 1, 'unit_price' => 185000000],
                    ['description' => 'Integrasi sistem melalui layanan API', 'unit' => 'sistem', 'quantity' => 3, 'unit_price' => 24000000],
                    ['description' => 'Lisensi aplikasi untuk pengguna operasional', 'unit' => 'pengguna', 'quantity' => 50, 'unit_price' => 1200000],
                    ['description' => 'Pelatihan dan pendampingan pengguna', 'unit' => 'hari', 'quantity' => 4, 'unit_price' => 5000000],
                    ['description' => 'Dukungan teknis dan pemeliharaan sistem', 'unit' => 'bulan', 'quantity' => 12, 'unit_price' => 8000000],
                ],
            ],
        ];

        foreach ($contracts as $data) {
            $boqItems = $data['boq_items'];
            unset($data['boq_items']);

            $contract = Contract::withTrashed()->firstOrNew([
                'contract_number' => $data['contract_number'],
            ]);
            $contract->fill(array_merge($data, [
                'client_id' => $client->id,
                'vendor_name' => $client->name,
                'status' => 'active',
                'grace_period_days' => 30,
                'created_by' => $owner->id,
            ]));

            if ($contract->trashed()) {
                $contract->restore();
            }

            $contract->save();

            $descriptions = [];
            $total = 0;
            foreach ($boqItems as $item) {
                $item['subtotal'] = round($item['quantity'] * $item['unit_price'], 2);
                $descriptions[] = $item['description'];
                $total += $item['subtotal'];

                $contract->boqItems()->updateOrCreate(
                    ['description' => $item['description']],
                    $item
                );
            }

            $contract->boqItems()->whereNotIn('description', $descriptions)->delete();
            $contract->update(['dpp' => $total]);
        }
    }
}