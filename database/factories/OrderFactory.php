<?php

namespace Database\Factories;

use App\Models\Customers;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customers::query()
                ->inRandomOrder()
                ->first()
                ->id,
            'product_id' => Product::query()
                ->inRandomOrder()
                ->first()
                ->id,
            'uuid' => $this->faker->uuid,
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'total' => $this->faker->randomFloat(2, 1, 1000),
            'discount' => $this->faker->randomFloat(2, 1, 1000),
            'campaigns' => $this->faker->randomElement(['10_PERCENT_OVER_1000', 'BUY_6_GET_1_CATEGORY_2', '20_PERCENT_OVER_2_ITEMS_CATEGORY_1']),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'declined']),
            'notes' => $this->faker->text,
        ];
    }
}
