<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractAudit;
use App\Models\ContractNotification;
use Illuminate\Support\Str;

class ContractRenewTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_renew_creates_audit_and_notification()
    {
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password'
        ]);

        // Create a client
        $client = Client::create([
            'name' => 'Acme Corp',
            'email' => 'acme@example.com',
            'status' => 'active'
        ]);

        // Create a contract that ends today
        $contract = Contract::create([
            'contract_number' => 'TEST-001',
            'contract_name' => 'Test Contract',
            'client_id' => $client->id,
            'start_date' => now()->subDays(30)->toDateString(),
            'end_date' => now()->toDateString(),
            'grace_period_days' => 30,
            'status' => 'active',
            'created_by' => $user->id
        ]);

        $this->actingAs($user);

        $newEnd = now()->addDays(60)->toDateString();

        $response = $this->postJson(route('contracts.renew', $contract), [
            'new_end_date' => $newEnd,
            'note' => 'Extended for testing'
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        // Refresh contract
        $contract->refresh();

        $this->assertEquals($newEnd, $contract->end_date->toDateString());

        // Assert audit record exists
        $this->assertDatabaseHas('contract_audits', [
            'contract_id' => $contract->id,
            'action' => 'renew'
        ]);

        // Assert notification created
        $this->assertDatabaseHas('notifications', [
            'contract_id' => $contract->id,
            'type' => ContractNotification::TYPE_RENEWAL
        ]);
    }
}
