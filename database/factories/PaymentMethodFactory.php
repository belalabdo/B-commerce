<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(10, 13),
            'method_name' => $this->faker->word(),
            'account_number' => $this->faker->regexify("/^[0-9]{16}$/"),
            'provider' => $this->faker->word(),
            'expiry_date' => $this->faker->regexify("/\b(0[1-9]|1[0-2])\/\d{2}\b/"),
            'is_default' => $this->faker->randomElement(["0", "1"]),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
