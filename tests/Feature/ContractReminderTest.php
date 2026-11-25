<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\ContractNotification;
use App\Models\User;
use App\Services\ContractNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_reminder_creates_an_in_app_notification_without_external_messaging(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $contract = Contract::factory()->create([
            'created_by' => $user->id,
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('contracts.send-reminder', $contract));

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Reminder kontrak berhasil dibuat sebagai notifikasi.')
            ->assertJsonPath('notification.contract_id', $contract->id)
            ->assertJsonPath('notification.data.manual', true);

        $this->assertDatabaseHas('notifications', [
            'contract_id' => $contract->id,
            'type' => ContractNotification::TYPE_EXPIRY,
            'is_read' => false,
        ]);

        $this->getJson(route('api.notifications.unread-count'))
            ->assertOk()
            ->assertJson(['count' => 1]);
    }

    public function test_expiry_notification_factory_persists_an_in_app_notification(): void
    {
        $contract = Contract::factory()->create([
            'end_date' => now()->addDays(7)->toDateString(),
        ]);

        $notification = ContractNotification::createExpiryNotification($contract, 7);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'contract_id' => $contract->id,
            'type' => ContractNotification::TYPE_EXPIRY,
        ]);
    }

    public function test_contract_detail_shows_reminder_button_for_active_contract(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $contract = Contract::factory()->create([
            'created_by' => $user->id,
            'status' => 'active',
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $this->actingAs($user)
            ->get(route('contracts.show', $contract))
            ->assertOk()
            ->assertSee('Kirim Reminder')
            ->assertSee(route('contracts.send-reminder', $contract), false)
            ->assertSee('contractReminderFeedback', false);
    }

    public function test_send_reminder_rejects_ineligible_contract_status(): void
    {
        $user = User::factory()->create();
        $contract = Contract::factory()->create([
            'created_by' => $user->id,
            'status' => 'terminated',
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $this->actingAs($user)
            ->postJson(route('contracts.send-reminder', $contract))
            ->assertStatus(422)
            ->assertJsonPath('success', false);

        $this->assertDatabaseMissing('notifications', [
            'contract_id' => $contract->id,
        ]);
    }

    public function test_send_reminder_prevents_duplicate_within_five_minutes(): void
    {
        $user = User::factory()->create();
        $contract = Contract::factory()->create([
            'created_by' => $user->id,
            'status' => 'active',
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $this->actingAs($user)
            ->postJson(route('contracts.send-reminder', $contract))
            ->assertOk();

        $this->actingAs($user)
            ->postJson(route('contracts.send-reminder', $contract))
            ->assertStatus(409)
            ->assertJsonPath('duplicate', true);

        $this->assertDatabaseCount('notifications', 1);
    }

    public function test_grace_period_contract_receives_automatic_reminder_for_30_days(): void
    {
        $contract = Contract::factory()->create([
            'status' => 'active',
            'end_date' => now()->toDateString(),
            'grace_period_days' => 30,
        ]);

        $results = (new ContractNotificationService())->checkAndSendNotifications();

        $contract->refresh();
        $this->assertSame('grace_period', $contract->status);
        $this->assertDatabaseHas('notifications', [
            'contract_id' => $contract->id,
            'type' => ContractNotification::TYPE_GRACE_PERIOD,
        ]);
        $this->assertGreaterThanOrEqual(1, $results['grace_period']);
    }

    public function test_contract_automatically_expires_after_30_day_grace_period(): void
    {
        $contract = Contract::factory()->create([
            'status' => 'grace_period',
            'end_date' => now()->subDays(30)->toDateString(),
            'grace_period_days' => 30,
        ]);

        (new ContractNotificationService())->checkAndSendNotifications();

        $contract->refresh();
        $this->assertSame('expired', $contract->status);
        $this->assertDatabaseHas('notifications', [
            'contract_id' => $contract->id,
            'type' => ContractNotification::TYPE_STATUS_CHANGE,
        ]);

        $countAfterFirstRun = ContractNotification::where('contract_id', $contract->id)->count();

        (new ContractNotificationService())->checkAndSendNotifications();

        $this->assertSame(
            $countAfterFirstRun,
            ContractNotification::where('contract_id', $contract->id)->count()
        );
    }
}
