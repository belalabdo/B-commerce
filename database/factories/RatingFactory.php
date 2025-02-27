<?php

namespace Database\Factories;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    protected $model = Rating::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" =>  $this->faker->numberBetween(10, 13),
            "product_id" =>  $this->faker->numberBetween(3, 476),
            "rate" => $this->faker->numberBetween(0, 5),
            "created_at" => now(),
            "updated_at" => now()
        ];
    }
}
