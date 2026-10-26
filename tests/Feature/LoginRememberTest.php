<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LoginRememberTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_remember_me_sets_persistent_login_cookie(): void
    {
        Event::fake([Login::class]);

        $user = User::factory()->create([
            'password' => 'password',
            'remember_token' => null,
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $response->assertRedirect('/dashboard');
    Event::assertDispatched(Login::class, fn (Login $event) => $event->remember);
        $this->assertNotNull($user->fresh()->getRememberToken());
    }
}