<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseFactory extends Factory
{
    public function definition()
    {
        return [
            'buyer_id' => User::factory(),
            'item_id' => Item::factory(),
            'postal_code' => $this->faker->numerify('###-####'),
            'address' => $this->faker->address(),
            'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
            'total_price' => $this->faker->numberBetween(100, 10000),
            'status' => Purchase::STATUS_PAID,
        ];
    }
}
