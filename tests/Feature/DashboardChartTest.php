<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardChartTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_monthly_chart_returns_six_months_for_authenticated_user(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-29 10:00:00'));

        $user = User::factory()->create();
        $client = Client::factory()->create();

        Contract::create([
            'contract_name' => 'Monthly chart contract',
            'client_id' => $client->id,
            'created_by' => $user->id,
            'start_date' => '2026-01-01',
            'end_date' => '2027-02-28',
            'status' => 'active',
            'grace_period_days' => 30,
        ]);

        $response = $this->actingAs($user)->getJson('/reports/distribution?period=monthly');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'labels' => [
                        'Oct 2026',
                        'Nov 2026',
                        'Dec 2026',
                        'Jan 2027',
                        'Feb 2027',
                        'Mar 2027',
                    ],
                    'counts' => [0, 0, 0, 0, 1, 0],
                ],
            ]);
    }

    public function test_monthly_chart_requires_authentication(): void
    {
        $this->get('/reports/distribution?period=monthly')
            ->assertRedirect(route('login'));
    }
}
