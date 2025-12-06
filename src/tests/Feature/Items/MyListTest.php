<?php

namespace Tests\Feature\Items;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Purchase;

class MyListTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function マイリスト画面が表示できる()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
    }

    /** @test */
    public function いいねした商品だけが表示される()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $likedItem = Item::factory()->create(['name' => 'いいねした商品']);
        Item::factory()->create(['name' => 'いいねしていない商品']);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    /** @test */
    public function 購入済み商品は「sold」と表示される()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => '購入済み商品',
            'status' => Item::STATUS_SOLD,
        ]);

        Purchase::factory()->create([
            'buyer_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('sold-badge');
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $likedItem = Item::factory()->create([
            'name' => 'いいねした商品',
        ]);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->get('/?tab=mylist');
        $response->assertDontSee('いいねした商品');
    }
}
