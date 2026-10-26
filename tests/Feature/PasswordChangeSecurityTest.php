<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordChangeSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'password' => Hash::make('OldPassword123@'),
        ]);
    }

    /**
     * Test user can access settings page
     */
    public function test_user_can_access_settings_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('settings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('settings.index');
    }

    /**
     * Test password change with valid credentials
     */
    public function test_user_can_change_password_with_valid_credentials()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'NewPassword123@',
                'password_confirmation' => 'NewPassword123@',
            ]);

        $response->assertSessionHas('success', 'Password berhasil diubah. Silakan login kembali dengan password baru.');
        
        // Verify new password is hashed correctly
        $this->user->refresh();
        $this->assertTrue(Hash::check('NewPassword123@', $this->user->password));
    }

    /**
     * Test password change fails with incorrect current password
     */
    public function test_password_change_fails_with_incorrect_current_password()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'WrongPassword123@',
                'password' => 'NewPassword123@',
                'password_confirmation' => 'NewPassword123@',
            ]);

        $response->assertSessionHasErrors(['current_password']);
        $response->assertSessionHasErrors(
            ['current_password' => 'Password saat ini tidak sesuai']
        );
    }

    /**
     * Test password change fails without uppercase letter
     */
    public function test_password_change_fails_without_uppercase()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'newpassword123@',
                'password_confirmation' => 'newpassword123@',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test password change fails without lowercase letter
     */
    public function test_password_change_fails_without_lowercase()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'NEWPASSWORD123@',
                'password_confirmation' => 'NEWPASSWORD123@',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test password change fails without number
     */
    public function test_password_change_fails_without_number()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'NewPassword@NoNumber',
                'password_confirmation' => 'NewPassword@NoNumber',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test password change fails without special character
     */
    public function test_password_change_fails_without_special_char()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'NewPassword123NoSpecial',
                'password_confirmation' => 'NewPassword123NoSpecial',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test password change fails if password is less than 8 characters
     */
    public function test_password_change_fails_with_short_password()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'New123@',
                'password_confirmation' => 'New123@',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test password change fails when confirmation doesn't match
     */
    public function test_password_change_fails_when_confirmation_not_match()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'NewPassword123@',
                'password_confirmation' => 'DifferentPassword123@',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test password change fails when new password is same as current
     */
    public function test_password_change_fails_when_same_as_current()
    {
        $response = $this->actingAs($this->user)
            ->post(route('settings.changePassword'), [
                'current_password' => 'OldPassword123@',
                'password' => 'OldPassword123@',
                'password_confirmation' => 'OldPassword123@',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test unauthenticated user cannot access settings
     */
    public function test_unauthenticated_user_cannot_access_settings()
    {
        $response = $this->get(route('settings.index'));
        
        $response->assertRedirectToRoute('login');
    }
}
