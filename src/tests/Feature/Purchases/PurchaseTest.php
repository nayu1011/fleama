<?php

namespace Tests\Feature\Purchases;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入するボタン押下で購入が完了する()
    {
        /** @var User $buyer */
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($buyer);

        $item = Item::factory()->create([
            'price' => 5000,
            'status' => Item::STATUS_LISTING,
        ]);

        session([
            'address' => [
                'postal_code' => '123-4567',
                'address' => '東京都新宿区西新宿2-8-1',
                'building' => '新宿モノリスビル',
            ],
            'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
        ]);

        $response = $this->post("/purchase/{$item->id}",['payment_method' => session('payment_method')]);
        $response->assertRedirect(route('purchases.success'));

        $this->assertDatabaseHas('purchases', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'total_price' => 5000,
            'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => Item::STATUS_SOLD,
        ]);
    }

    /** @test */
    public function 購入した商品がsoldとして表示される()
    {
        /** @var User $buyer */
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($buyer);

        $item = Item::factory()->create([
            'price' => 5000,
            'status' => Item::STATUS_LISTING,
        ]);

        session([
            'address' => [
                'postal_code' => '123-4567',
                'address' => '東京都新宿区西新宿2-8-1',
                'building' => '新宿モノリスビル',
            ],
            'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
        ]);

        $response = $this->post("/purchase/{$item->id}",['payment_method' => session('payment_method')]);
        $response = $this->get(route('items.index'));

        $response -> assertStatus(200)
                  -> assertSee('sold-badge');
    }

    /** @test */
    public function 購入した商品がプロフィールの購入した商品一覧に表示される()
    {
        /** @var User $buyer */
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($buyer);

        $buyedItem = Item::factory()->create([
            'name' => '購入した商品',
            'price' => 5000,
            'status' => Item::STATUS_LISTING,
        ]);

        $otherItem = Item::factory()->create([
            'name' => '購入していない商品',
            'price' => 3000,
            'status' => Item::STATUS_LISTING,
        ]);

        session([
            'address' => [
                'postal_code' => '123-4567',
                'address' => '東京都新宿区西新宿2-8-1',
                'building' => '新宿モノリスビル',
            ],
            'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
        ]);

        $this->post("/purchase/{$buyedItem->id}",['payment_method' => session('payment_method')]);
        $this->get('/mypage?bage=buy')
            -> assertStatus(200)
            -> assertSee('購入した商品')
            -> assertDontSee('購入していない商品');
    }

}
