<?php

namespace Tests\Feature\Items;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Category;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品詳細ページが表示できる()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create([
            'seller_id' => $user->id,
            'name' => '腕時計',
            'brand_name' => 'ロレックス',
            'description' => '高級な腕時計です。',
            'price' => 50000,
            'image_path' => 'items/sample.jpg',
            'condition' => 0,
            'like_count' => 99999,
            'status' => Item::STATUS_LISTING,
        ]);

        $category = Category::factory()->create(['name' => 'アクセサリー']);
        $item->categories()->attach($category->id);
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '素晴らしい商品ですね！',
        ]);

        $this->get("/item/{$item->id}")
        ->assertStatus(200)
        ->assertSee('items/sample.jpg')
        ->assertSee('腕時計')
        ->assertSee('ロレックス')
        ->assertSee('50,000')
        ->assertSee('99999')
        ->assertSee('高級な腕時計です。')
        ->assertSee('アクセサリー')
        ->assertSee('新品')
        ->assertSee('コメント(1)')
        ->assertSee($user->name)
        ->assertSee('素晴らしい商品ですね！');
    }

}
