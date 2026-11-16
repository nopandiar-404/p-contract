<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractNumberGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_generator_uses_division_initials_and_contract_start_date(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'division' => 'Pengelolaan Kontrak',
        ]);

        $this->actingAs($user)
            ->getJson(route('contracts.generate-number', ['start_date' => '2026-11-15']))
            ->assertOk()
            ->assertExactJson([
                'contract_number' => 'PLD-PK-2026-11-15',
            ]);
    }

    public function test_generator_adds_a_sequence_only_when_the_base_number_is_taken(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'division' => 'Pengelolaan Kontrak',
        ]);

        Contract::factory()->create([
            'contract_number' => 'PLD-PK-2026-11-15',
            'start_date' => '2026-11-15',
            'end_date' => '2027-11-15',
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->getJson(route('contracts.generate-number', ['start_date' => '2026-11-15']))
            ->assertOk()
            ->assertExactJson([
                'contract_number' => 'PLD-PK-2026-11-15-002',
            ]);
    }

    public function test_generator_requires_a_division_name_on_the_user_profile(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'division' => null,
        ]);

        $this->actingAs($user)
            ->getJson(route('contracts.generate-number', ['start_date' => '2026-11-15']))
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'Lengkapi nama divisi pada profil sebelum membuat nomor kontrak.',
            ]);
    }
}
