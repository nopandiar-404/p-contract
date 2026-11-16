<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => preg_replace('/[^0-9]/', '', $this->faker->phoneNumber()),
            'address' => $this->faker->address(),
            'pic_name' => $this->faker->name(),
            'pic_phone' => preg_replace('/[^0-9]/', '', $this->faker->phoneNumber()),
            'pic_email' => $this->faker->companyEmail(),
            'status' => 'active',
            'notes' => null,
        ];
    }
}
