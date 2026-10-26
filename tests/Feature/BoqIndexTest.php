<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractBoqItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * @method \Illuminate\Testing\TestResponse get(string $uri, array $headers = [])
 */
class BoqIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_boq_quantity_format_hides_trailing_decimal_zeros(): void
    {
        $this->assertSame('1', (new ContractBoqItem(['quantity' => '1.000']))->formatted_quantity);
        $this->assertSame('1,25', (new ContractBoqItem(['quantity' => '1.250']))->formatted_quantity);
        $this->assertSame('1.000', (new ContractBoqItem(['quantity' => '1000.000']))->formatted_quantity);
    }

    public function test_boq_menu_lists_items_linked_to_their_contract(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Pelindo Vendor']);
        $contract = Contract::create([
            'contract_number' => 'BOQ-001',
            'contract_name' => 'Pemeliharaan Dermaga',
            'contract_type' => 'contract_jasa',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'created_by' => $user->id,
        ]);
        $contract->boqItems()->create([
            'description' => 'Pembersihan area dermaga',
            'unit' => 'm2',
            'quantity' => 12.5,
            'unit_price' => 40000,
            'subtotal' => 500000,
        ]);

        Auth::login($user);
        $response = $this->get(route('boq.index'));

        $response->assertOk()
            ->assertSeeInOrder(['No. Kontrak', 'Kontrak', 'Vendor'])
            ->assertSee('Pembersihan area dermaga')
            ->assertSee('BOQ-001')
            ->assertSee('Pelindo Vendor')
            ->assertSee('500.000');
    }

    public function test_boq_menu_hides_other_users_contract_items(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $client = Client::factory()->create();
        $contract = Contract::create([
            'contract_number' => 'PRIVATE-BOQ',
            'contract_name' => 'Kontrak privat',
            'contract_type' => 'contract_barang',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'created_by' => $owner->id,
        ]);
        $contract->boqItems()->create([
            'description' => 'Item privat',
            'unit' => 'unit',
            'quantity' => 1,
            'unit_price' => 100,
            'subtotal' => 100,
        ]);

        Auth::login($user);
        $response = $this->get(route('boq.index'));

        $response->assertOk()->assertDontSee('Item privat');
    }

    public function test_boq_menu_paginates_items_and_preserves_search(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $contract = Contract::create([
            'contract_number' => 'BOQ-PAGINATION',
            'contract_name' => 'Kontrak pagination',
            'contract_type' => 'contract_jasa',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        for ($number = 1; $number <= 11; $number++) {
            $contract->boqItems()->create([
                'description' => "Pekerjaan BOQ {$number}",
                'unit' => 'unit',
                'quantity' => 1,
                'unit_price' => 100,
                'subtotal' => 100,
            ]);
        }

        Auth::login($user);
        $firstPage = $this->get(route('boq.index', ['q' => 'Pekerjaan BOQ']));

        $firstPage->assertOk()
            ->assertSee('Menampilkan 1-10 dari')
            ->assertSee('page=2', false)
            ->assertSee('Pekerjaan BOQ 10')
            ->assertDontSee('Pekerjaan BOQ 11');

        $secondPage = $this->get(route('boq.index', ['q' => 'Pekerjaan BOQ', 'page' => 2]));
        $secondPage->assertOk()
            ->assertSee('Menampilkan 11-11 dari')
            ->assertSee('Pekerjaan BOQ 11')
            ->assertDontSee('Pekerjaan BOQ 10');

        $largerPage = $this->get(route('boq.index', ['q' => 'Pekerjaan BOQ', 'per_page' => 25]));
        $largerPage->assertOk()
            ->assertSee('1-11 dari')
            ->assertSee('Pekerjaan BOQ 11');
    }
}