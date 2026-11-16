<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contract;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-2 years', '+1 month');
        $startCarbon = Carbon::instance($start);
        $endCarbon = (clone $startCarbon)->addMonths($this->faker->numberBetween(1, 24));

        $status = $endCarbon->lt(Carbon::now()) ? 'expired' : 'active';

        return [
            'contract_number' => null, // generated in model boot if empty
            'contract_name' => $this->faker->catchPhrase(),
            'client_id' => Client::factory(),
            'description' => $this->faker->sentence(),
            'start_date' => $startCarbon->toDateString(),
            'end_date' => $endCarbon->toDateString(),
            'status' => $status,
            'grace_period_days' => 30,
            'created_by' => User::factory(),
        ];
    }
}
