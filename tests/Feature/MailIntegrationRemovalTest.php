<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailIntegrationRemovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_gmail_settings_and_reminder_ui_are_removed(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $client = Client::factory()->create();
        $contract = Contract::factory()->create([
            'client_id' => $client->id,
            'created_by' => $user->id,
            'status' => 'active',
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        $this->actingAs($user)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertDontSee('Gmail API')
            ->assertDontSee('gmail-section');

        $this->get(route('contracts.index'))
            ->assertOk()
            ->assertDontSee('btn-gmail-reminder');

        $this->get(route('contracts.show', $contract))
            ->assertOk()
            ->assertDontSee('btn-gmail-reminder')
            ->assertDontSee('gmailReminderModal');
    }

    public function test_gmail_oauth_watch_webhook_and_reminder_endpoints_are_removed(): void
    {
        $this->assertSame(404, $this->get('/gmail/oauth')->getStatusCode());
        $this->assertSame(404, $this->get('/gmail/callback')->getStatusCode());
        $this->assertSame(404, $this->get('/settings/gmail')->getStatusCode());
        $this->assertSame(404, $this->post('/gmail/watch')->getStatusCode());
        $this->assertSame(404, $this->post('/api/gmail/webhook')->getStatusCode());
        $this->assertSame(404, $this->post('/contracts/1/send-gmail-reminder')->getStatusCode());
    }
}
