<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @method \Illuminate\Testing\TestResponse get(string $uri, array $headers = [])
 * @method \Illuminate\Testing\TestResponse post(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse put(string $uri, array $data = [], array $headers = [])
 * @method $this assertDontSee(string $text, bool $escaped = true)
 * @method static $this assertDatabaseHas(string $table, array $data = [], ?string $connection = null)
 * @method static $this assertDatabaseMissing(string $table, array $data = [], ?string $connection = null)
 */
class ContractTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_type_catalog_lists_the_three_supported_types(): void
    {
        Auth::login(User::factory()->create());
        $response = $this->get(route('contract-types.index'));

        $response->assertOk()
            ->assertSee('Kontrak Barang')
            ->assertSee('Kontrak Jasa')
            ->assertSee('License');
    }

    public function test_contract_type_is_saved_when_creating_a_contract(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $client = Client::factory()->create();
        Auth::login($user);

        $response = $this->post(route('contracts.store'), [
            'contract_number' => 'TYPE-001',
            'contract_name' => 'Kontrak Lisensi Aplikasi',
            'contract_type' => 'license',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'pdf_files' => [UploadedFile::fake()->createWithContent('contract.pdf', "%PDF-1.4\nContract")],
        ]);

        $contract = Contract::where('contract_number', 'TYPE-001')->firstOrFail();

        $response->assertRedirect(route('contracts.show', $contract));
        $this->assertSame('license', $contract->contract_type);
    }

    public function test_contract_boq_items_are_saved_and_total_is_synced(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $client = Client::factory()->create();
        Auth::login($user);

        $response = $this->post(route('contracts.store'), [
            'contract_number' => 'BOQ-SYNC-001',
            'contract_name' => 'Kontrak Pengadaan',
            'contract_type' => 'contract_barang',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'boq_items' => [
                ['description' => 'Material A', 'unit' => 'unit', 'quantity' => '2.5', 'unit_price' => '40000'],
            ],
            'pdf_files' => [UploadedFile::fake()->createWithContent('contract.pdf', "%PDF-1.4\nContract")],
        ]);

        $contract = Contract::where('contract_number', 'BOQ-SYNC-001')->firstOrFail();

        $response->assertRedirect(route('contracts.show', $contract));
        $this->assertDatabaseHas('contract_boq_items', [
            'contract_id' => $contract->id,
            'description' => 'Material A',
            'subtotal' => 100000,
        ]);
        $this->assertSame('100000.00', $contract->fresh()->dpp);
    }

    public function test_clearing_contract_boq_items_clears_the_synced_total(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $contract = Contract::create([
            'contract_number' => 'BOQ-SYNC-002',
            'contract_name' => 'Kontrak Jasa',
            'contract_type' => 'contract_jasa',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'created_by' => $user->id,
            'dpp' => 100000,
        ]);
        $contract->boqItems()->create([
            'description' => 'Item lama',
            'unit' => 'unit',
            'quantity' => 1,
            'unit_price' => 100000,
            'subtotal' => 100000,
        ]);

        Auth::login($user);
        $response = $this->put(route('contracts.update', $contract), [
            'contract_number' => $contract->contract_number,
            'contract_name' => $contract->contract_name,
            'contract_type' => 'contract_jasa',
            'client_id' => $client->id,
            'start_date' => $contract->start_date->toDateString(),
            'end_date' => $contract->end_date->toDateString(),
            'status' => 'active',
            'boq_items' => [['description' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '']],
        ]);

        $response->assertRedirect(route('contracts.show', $contract));
        $this->assertDatabaseMissing('contract_boq_items', ['contract_id' => $contract->id]);
        $this->assertNull($contract->fresh()->dpp);
    }

    public function test_contract_list_filters_by_contract_type_and_shows_the_active_filter(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        Auth::login($user);

        Contract::create([
            'contract_number' => 'FILTER-BRG-001',
            'contract_name' => 'Pengadaan Peralatan Dermaga',
            'contract_type' => 'contract_barang',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'created_by' => $user->id,
        ]);
        Contract::create([
            'contract_number' => 'FILTER-JSA-001',
            'contract_name' => 'Pemeliharaan Jaringan Utilitas',
            'contract_type' => 'contract_jasa',
            'client_id' => $client->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('contracts.index', ['contract_type' => 'contract_barang']));

        $response->assertOk()
            ->assertSee('Pengadaan Peralatan Dermaga')
            ->assertSee('Jenis: Kontrak Barang')
            ->assertDontSee('Pemeliharaan Jaringan Utilitas');
    }
}