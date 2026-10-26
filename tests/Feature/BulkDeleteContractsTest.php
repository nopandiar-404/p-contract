<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * @method $this from(string $uri, array $headers = [])
 * @method \Illuminate\Testing\TestResponse post(string $uri, array $data = [], array $headers = [])
 * @method static $this assertDatabaseHas(string $table, array $data = [], ?string $connection = null)
 * @method static $this assertDatabaseMissing(string $table, array $data = [], ?string $connection = null)
 */
class BulkDeleteContractsTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_delete_respects_filters_ownership_and_active_contract_protection(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $client = Client::factory()->create();
        $expired = $this->createContract($user, $client, 'EXP-DELETE', 'expired');
        $active = $this->createContract($user, $client, 'ACT-KEEP', 'active');
        $otherExpired = $this->createContract($user, $client, 'OTHER-KEEP', 'expired');
        $foreignExpired = $this->createContract($otherUser, $client, 'FOREIGN-KEEP', 'expired');
        $boqItem = $expired->boqItems()->create([
            'description' => 'Item untuk dihapus',
            'unit' => 'unit',
            'quantity' => 1,
            'unit_price' => 500,
            'subtotal' => 500,
        ]);

        $this->assertDatabaseHas('contracts', [
            'id' => $expired->id,
            'contract_number' => 'EXP-DELETE',
            'status' => 'expired',
            'created_by' => $user->id,
        ]);

        Auth::login($user);
        $response = $this->from(route('contracts.index', [
            'status' => 'expired',
            'contract_number' => 'EXP-DELETE',
        ]))->post(route('contracts.bulk-delete'), [
            'status' => 'expired',
            'contract_number' => 'EXP-DELETE',
        ]);
        $response->assertRedirect(route('contracts.index'))
            ->assertSessionHas('success', '1 kontrak berhasil dihapus.');
        $this->assertNotNull($expired->fresh()->deleted_at);
        $this->assertNull($active->fresh()->deleted_at);
        $this->assertNull($otherExpired->fresh()->deleted_at);
        $this->assertNull($foreignExpired->fresh()->deleted_at);
        $this->assertDatabaseMissing('contract_boq_items', ['id' => $boqItem->id]);
    }

    public function test_bulk_delete_only_deletes_selected_contracts_and_protects_active_contracts(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $selectedExpired = $this->createContract($user, $client, 'SELECTED-EXPIRED', 'expired');
        $selectedActive = $this->createContract($user, $client, 'SELECTED-ACTIVE', 'active');
        $unselectedExpired = $this->createContract($user, $client, 'UNSELECTED-EXPIRED', 'expired');
        $boqItem = $selectedExpired->boqItems()->create([
            'description' => 'Item terpilih',
            'unit' => 'unit',
            'quantity' => 1,
            'unit_price' => 500,
            'subtotal' => 500,
        ]);

        Auth::login($user);
        $response = $this->post(route('contracts.bulk-delete'), [
            'contract_ids' => [$selectedExpired->id, $selectedActive->id],
        ]);

        $response->assertRedirect(route('contracts.index'))
            ->assertSessionHas('success', '1 kontrak berhasil dihapus. 1 kontrak aktif/masa tenggang dilewati.');
        $this->assertNotNull($selectedExpired->fresh()->deleted_at);
        $this->assertNull($selectedActive->fresh()->deleted_at);
        $this->assertNull($unselectedExpired->fresh()->deleted_at);
        $this->assertDatabaseMissing('contract_boq_items', ['id' => $boqItem->id]);
    }

    public function test_bulk_delete_rejects_selected_contracts_owned_by_another_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $client = Client::factory()->create();
        $foreignContract = $this->createContract($otherUser, $client, 'FOREIGN-SELECTED', 'expired');

        Auth::login($user);
        $response = $this->post(route('contracts.bulk-delete'), [
            'contract_ids' => [$foreignContract->id],
        ]);

        $response->assertForbidden();
        $this->assertNull($foreignContract->fresh()->deleted_at);
    }

    public function test_bulk_delete_requires_explicit_force_to_delete_active_contracts(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $active = $this->createContract($user, $client, 'ACT-FORCE', 'active');

        Auth::login($user);
        $response = $this->post(route('contracts.bulk-delete'), [
            'contract_number' => 'ACT-FORCE',
            'force' => '1',
        ]);

        $response->assertRedirect(route('contracts.index'))
            ->assertSessionHas('success', '1 kontrak berhasil dihapus.');
        $this->assertNotNull($active->fresh()->deleted_at);
    }

    public function test_bulk_delete_respects_contract_type_filter(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        $goodsContract = $this->createContract($user, $client, 'TYPE-BARANG', 'expired');
        $serviceContract = $this->createContract($user, $client, 'TYPE-JASA', 'expired');
        $serviceContract->update(['contract_type' => 'contract_jasa']);

        Auth::login($user);
        $response = $this->post(route('contracts.bulk-delete'), [
            'contract_type' => 'contract_barang',
        ]);

        $response->assertRedirect(route('contracts.index'))
            ->assertSessionHas('success', '1 kontrak berhasil dihapus.');
        $this->assertNotNull($goodsContract->fresh()->deleted_at);
        $this->assertNull($serviceContract->fresh()->deleted_at);
    }

    private function createContract(User $owner, Client $client, string $number, string $status): Contract
    {
        return Contract::create([
            'contract_number' => $number,
            'contract_name' => $number,
            'contract_type' => 'contract_barang',
            'client_id' => $client->id,
            'start_date' => now()->subDays(60)->toDateString(),
            'end_date' => $status === 'expired'
                ? now()->subDays(45)->toDateString()
                : now()->addMonth()->toDateString(),
            'status' => $status,
            'created_by' => $owner->id,
        ]);
    }
}