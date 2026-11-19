<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\ContractNotification;
use App\Models\User;
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
}
