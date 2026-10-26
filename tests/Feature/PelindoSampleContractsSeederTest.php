<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\User;
use Database\Seeders\PelindoSampleContractsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @method static $this assertDatabaseHas(string $table, array $data = [], ?string $connection = null)
 */
class PelindoSampleContractsSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelindo_indonesian_sample_contracts_and_boq_are_idempotently_seeded(): void
    {
        $seeder = new PelindoSampleContractsSeeder();
        $seeder->run();
        $seeder->run();

        $utility = Contract::where('contract_number', 'PLD-UTL-2026-001')->firstOrFail();
        $informationSystem = Contract::where('contract_number', 'PLD-SI-2026-002')->firstOrFail();

        $this->assertSame('Pekerjaan Pemeliharaan Jaringan Utilitas Dermaga', $utility->contract_name);
        $this->assertSame('Pekerjaan Pengembangan Sistem Informasi Operasional Pelabuhan', $informationSystem->contract_name);
        $this->assertCount(5, $utility->boqItems);
        $this->assertCount(6, $informationSystem->boqItems);
        $this->assertSame('286250000.00', $utility->dpp);
        $this->assertSame('465000000.00', $informationSystem->dpp);
        $this->assertSame(2, Contract::where('client_id', $utility->client_id)->count());
        $this->assertDatabaseHas('clients', ['email' => 'pelindo.demo@example.test']);
    }

    public function test_pelindo_contracts_are_owned_by_the_local_demo_user_when_available(): void
    {
        $demoUser = User::factory()->create([
            'email' => 'local-test@example.com',
            'name' => 'Local Test',
            'role' => 'user',
        ]);

        (new PelindoSampleContractsSeeder())->run();

        $this->assertSame(
            2,
            Contract::where('created_by', $demoUser->id)
                ->whereIn('contract_number', ['PLD-UTL-2026-001', 'PLD-SI-2026-002'])
                ->count()
        );
    }
}