<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
            'name' => $this->faker->name(),
            'product_code' => $this->faker->numberBetween($min = 11111, $max = 99999),
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween($min = 50, $max = 500),
            'quantity' => $this->faker->numberBetween($min = 1, $max = 100),
            'tags' => '#developer',
        ];
    }
}
