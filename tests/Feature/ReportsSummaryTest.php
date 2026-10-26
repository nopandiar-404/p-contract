<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Contract;
use App\Models\Client;
use Carbon\Carbon;

class ReportsSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_summary_returns_expected_structure()
    {
        // create a test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test.user@example.com',
            'password' => bcrypt('secret'),
        ]);

        // create a client and sample contracts
        $client = Client::create([
            'name' => 'ACME Corp',
            'email' => 'client@example.com'
        ]);

        Contract::create([
            'contract_name' => 'Active Contract',
            'client_id' => $client->id,
            'created_by' => $user->id,
            'start_date' => Carbon::now()->subMonths(3)->toDateString(),
            'end_date' => Carbon::now()->addMonths(6)->toDateString(),
            'status' => 'active',
            'grace_period_days' => 30,
        ]);

        Contract::create([
            'contract_name' => 'Grace Contract',
            'created_by' => $user->id,
            'client_id' => $client->id,
            'start_date' => Carbon::now()->subMonths(6)->toDateString(),
            'end_date' => Carbon::now()->subDays(10)->toDateString(),
            'status' => 'grace_period',
            'grace_period_days' => 30,
        ]);

        Contract::create([
            'contract_name' => 'Expired Contract',
            'created_by' => $user->id,
            'client_id' => $client->id,
            'start_date' => Carbon::now()->subYears(2)->toDateString(),
            'end_date' => Carbon::now()->subYears(1)->toDateString(),
            'status' => 'expired',
            'grace_period_days' => 30,
        ]);

        // act as the user
        $this->actingAs($user);

        // call the API route
        $response = $this->getJson('/api/reports/summary');

        $response->assertStatus(200);

        // The controller returns { success: true, data: { active, grace_period, expired, total } }
        $response->assertJsonStructure([
            'success',
            'data' => [
                'active',
                'grace_period',
                'expired',
                'total'
            ]
        ]);

        $json = $response->json('data');
        $this->assertEquals(1, $json['active']);
        $this->assertEquals(1, $json['grace_period']);
        $this->assertEquals(1, $json['expired']);
        $this->assertEquals(3, $json['total']);
    }
}
