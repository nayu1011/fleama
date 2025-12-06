<?php

namespace Tests\Feature\Purchases;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseAddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 変更した住所が購入確認画面に表示される()
    {
        /** @var User $buyer */
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($buyer);

        $item = Item::factory()->create();

        session([
            'address' => [
                'postal_code' => '150-0043',
                'address' => '東京都渋谷区道玄坂1-2-3',
                'building' => '渋谷ビル',
            ]
        ]);

        // 住所編集画面にて住所を変更
        $response = $this->post("/purchase/address/{$item->id}",[
            'postal_code' => '123-4567',
            'address' => '東京都新宿区西新宿2-8-1',
            'building' => '新宿モノリスビル',
        ]);

        $response->assertRedirect(route('purchases.create', ['item_id' => $item->id]));

        $response = $this->get(route('purchases.create', ['item_id' => $item->id]));
        $response->assertSee('123-4567')
            ->assertSee('東京都新宿区西新宿2-8-1')
            ->assertSee('新宿モノリスビル')
            ->assertDontSee('150-0043')
            ->assertDontSee('東京都渋谷区道玄坂1-2-3')
            ->assertDontSee('渋谷ビル');
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        /** @var User $buyer */
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($buyer);

        $item = Item::factory()->create();

        session([
            'address' => [
                'postal_code' => '150-0043',
                'address' => '東京都渋谷区道玄坂1-2-3',
                'building' => '渋谷ビル',
            ],
            'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
        ]);

        // 住所編集画面にて住所を変更
        $response = $this->post("/purchase/address/{$item->id}",[
            'postal_code' => '123-4567',
            'address' => '東京都新宿区西新宿2-8-1',
            'building' => '新宿モノリスビル',
        ]);

        $response = $this->post("/purchase/{$item->id}",['payment_method' => session('payment_method')]);
        $response->assertRedirect(route('purchases.success'));

        $this->assertDatabaseHas('purchases', [
                'item_id' => $item->id,
                'buyer_id' => $buyer->id,
                'postal_code' => '123-4567',
                'address' => '東京都新宿区西新宿2-8-1',
                'building' => '新宿モノリスビル',
            ]);
    }
}
