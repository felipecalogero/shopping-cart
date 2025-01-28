<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        static $count = 1;

        $productName = 'Produto ' . $count++;

        return [
            'name' => $productName,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'description' => 'Pequena descrição sobre o produto ' . $productName,
        ];
    }
}
