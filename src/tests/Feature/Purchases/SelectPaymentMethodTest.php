<?php

namespace Tests\Feature\Purchases;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class SelectPaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 選択した支払い方法が承継画面に表示される()
    {
        /** @var User $buyer */
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($buyer);

        $item = Item::factory()->create();

        $response = $this->get("/purchase/{$item->id}?payment_method=" . Purchase::PAYMENT_CREDIT_CARD);

        $response->assertStatus(200);
        $this->assertMatchesRegularExpression('/payment-info__value">\s*クレジットカード\s*<\/div>/', $response->getContent(0));
    }

}
