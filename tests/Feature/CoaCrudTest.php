<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Coa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoaCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_sorts_by_code_ascending()
    {
        $user = User::factory()->create();

        Coa::factory()->create(['code' => 'B02', 'name' => 'Beta']);
        Coa::factory()->create(['code' => 'A01', 'name' => 'Alpha']);
        Coa::factory()->create(['code' => 'C03', 'name' => 'Charlie']);

        $response = $this->actingAs($user)->get('/coa');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['A01', 'B02', 'C03']);
    }

    public function test_create_creates_new_coa_and_redirects()
    {
        $user = User::factory()->create();

        $data = [
            'code' => 'TST01',
            'name' => 'Test Coa',
            'description' => 'Created by test',
        ];

        $response = $this->actingAs($user)->post(route('coa.store'), $data);

        $response->assertRedirect(route('coa.index'));
        $this->assertDatabaseHas('coas', ['code' => 'TST01', 'name' => 'Test Coa']);
    }

    public function test_update_changes_coa_values()
    {
        $user = User::factory()->create();

        $coa = Coa::factory()->create(['code' => 'UPD01', 'name' => 'Before']);

        $payload = ['code' => 'UPD01', 'name' => 'After', 'description' => 'Updated'];

        $response = $this->actingAs($user)->put(route('coa.update', $coa), $payload);

        $response->assertRedirect(route('coa.index'));
        $this->assertDatabaseHas('coas', ['id' => $coa->id, 'name' => 'After', 'description' => 'Updated']);
    }

    public function test_delete_removes_coa_from_database()
    {
        $user = User::factory()->create();

        $coa = Coa::factory()->create(['code' => 'DEL01', 'name' => 'To delete']);

        $response = $this->actingAs($user)->delete(route('coa.destroy', $coa));

        $response->assertRedirect(route('coa.index'));
        $this->assertDatabaseMissing('coas', ['id' => $coa->id]);
    }
}
