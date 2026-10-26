<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Coa;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coa>
 */
class CoaFactory extends Factory
{
    protected $model = Coa::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('??###')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
