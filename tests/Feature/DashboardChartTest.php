<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractNotification;
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

        /** @var User $user */
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

    public function test_dashboard_live_endpoints_require_authentication(): void
    {
        $this->getJson(route('api.dashboard.stats'))->assertUnauthorized();
        $this->getJson(route('api.notifications.unread-count'))->assertUnauthorized();
    }

    public function test_dashboard_live_endpoints_scope_counts_to_the_current_user(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-29 10:00:00'));

        /** @var User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $client = Client::factory()->create();

        $ownedActiveContract = Contract::create([
            'contract_number' => 'LIVE-ACTIVE-001',
            'contract_name' => 'Owned active contract',
            'client_id' => $client->id,
            'created_by' => $user->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-11-15',
            'status' => 'active',
            'grace_period_days' => 30,
        ]);

        Contract::create([
            'contract_number' => 'LIVE-OTHER-001',
            'contract_name' => 'Other user active contract',
            'client_id' => $client->id,
            'created_by' => $otherUser->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-11-15',
            'status' => 'active',
            'grace_period_days' => 30,
        ]);

        ContractNotification::create([
            'contract_id' => $ownedActiveContract->id,
            'type' => ContractNotification::TYPE_EXPIRY,
            'message' => 'Owned unread notification',
            'is_read' => false,
            'sent_date' => now(),
        ]);

        $otherContract = Contract::where('created_by', $otherUser->id)->firstOrFail();
        ContractNotification::create([
            'contract_id' => $otherContract->id,
            'type' => ContractNotification::TYPE_EXPIRY,
            'message' => 'Other user unread notification',
            'is_read' => false,
            'sent_date' => now(),
        ]);

        $this->actingAs($user)
            ->getJson(route('api.dashboard.stats'))
            ->assertOk()
            ->assertJson([
                'activeContracts' => 1,
                'gracePeriodContracts' => 0,
                'expiringThisMonth' => 1,
                'expiredContracts' => 0,
                'unreadNotifications' => 1,
            ]);

        $this->getJson(route('api.notifications.unread-count'))
            ->assertOk()
            ->assertJson(['count' => 1]);
    }

    public function test_dashboard_shows_separate_status_cards_with_counts_for_the_user(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-29 10:00:00'));

        /** @var User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $client = Client::factory()->create();

        Contract::create([
            'contract_number' => 'DASH-ACTIVE-001',
            'contract_name' => 'Active contract label',
            'client_id' => $client->id,
            'created_by' => $user->id,
            'start_date' => '2026-01-01',
            'end_date' => '2027-11-15',
            'status' => 'active',
            'grace_period_days' => 30,
        ]);

        Contract::create([
            'contract_number' => 'DASH-GRACE-001',
            'contract_name' => 'Grace period contract label',
            'client_id' => $client->id,
            'created_by' => $user->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-10-20',
            'status' => 'grace_period',
            'grace_period_days' => 30,
        ]);

        Contract::create([
            'contract_number' => 'DASH-OTHER-001',
            'contract_name' => 'Other user active contract',
            'client_id' => $client->id,
            'created_by' => $otherUser->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-11-15',
            'status' => 'active',
            'grace_period_days' => 30,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Kontrak Berjalan')
            ->assertSee('Kontrak Masa Tenggang')
            ->assertSee('images/crm-kontrak.png')
            ->assertSee('alt="CRM Kontrak"', false)
            ->assertSee('class="brand-name">SIMOKO</span>', false)
            ->assertDontSee('Notifikasi Terbaru')
            ->assertSee('data-status-card="active" data-count="1"', false)
            ->assertSee('data-status-card="grace-period" data-count="1"', false)
            ->assertSee('data-chart-height="180"', false)
            ->assertSee('data-labels="[&quot;Active contract label&quot;]"', false)
            ->assertSee('data-labels="[&quot;Grace period contract label&quot;]"', false);
    }
}
