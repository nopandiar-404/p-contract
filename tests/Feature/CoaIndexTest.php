<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Coa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CoaIndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function search_filters_results_by_code_or_name()
    {
        /** @var User $user */
        $user = User::factory()->create();

        Coa::factory()->create(['code' => 'ABC001', 'name' => 'Alpha Coa']);
        Coa::factory()->create(['code' => 'XYZ999', 'name' => 'Zulu Coa']);
        Coa::factory()->count(5)->create();

        $response = $this->actingAs($user)->get('/coa?q=ABC');

        $response->assertStatus(200);
        $response->assertSee('ABC001');
        $response->assertDontSee('XYZ999');
    }

    #[Test]
    public function pagination_preserves_search_query()
    {
        /** @var User $user */
        $user = User::factory()->create();

        // create 20 items matching the query so filtered results span multiple pages
        Coa::factory()->count(20)->create(['name' => 'contains testquery']);
        // add a few other items
        Coa::factory()->count(5)->create();

        // apply a query and ensure pagination links include the query param
        $response = $this->actingAs($user)->get('/coa?q=testquery');
        $response->assertStatus(200);
        $this->assertStringContainsString('q=testquery', $response->getContent());
    }
}
