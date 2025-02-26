<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'desc' => $this->faker->sentence(15),
            'price' => $this->faker->randomFloat(2, 5, 500), // Prices between 5 and 500
            'category_id' => $this->faker->numberBetween(1, 112),
            'brand_id' => $this->faker->numberBetween(1, 111),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'discount' => $this->faker->randomFloat(2, 0, 50), // Discount up to 50
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
