<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'seller_id' => User::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(100, 10000),
            'condition' => $this->faker->randomElement(array_keys(Item::CONDITIONS)),
            'status' => Item::STATUS_LISTING,
        ];
    }
}
