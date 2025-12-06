<?php

namespace Tests\Feature\Items;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        // 検索対象の商品を作成
        Item::factory()->create([
            'name' => '腕時計'
        ]);

        // 検索対象ではない商品を作成
        Item::factory()->create([
            'name' => 'スマートフォン'
        ]);

        $response = $this->get('/?keyword=時計');
        $response->assertSee('腕時計');
        $response->assertDontSee('スマートフォン');
    }

/** @test */
public function 検索状態がマイリストでも保持されている()
{
    /** @var User $user */
    $user = User::factory()->create(['email_verified_at' => now()]);
    $this->actingAs($user);

    // ヒットする商品（いいね済み）
    $watch = Item::factory()->create(['name' => '腕時計']);
    Favorite::factory()->create([
        'user_id' => $user->id,
        'item_id' => $watch->id
    ]);

    // ヒットしない商品（いいね済み）
    $shirt = Item::factory()->create(['name' => 'Tシャツ']);
    Favorite::factory()->create([
        'user_id' => $user->id,
        'item_id' => $shirt->id
    ]);


    /** Step1: ホームで検索実行 */
    $response = $this->get('/?keyword=時計');

    // ここでは「腕時計」が見える & 「Tシャツ」は見えない
    $response->assertSee('腕時計');
    $response->assertDontSee('Tシャツ');


    /** Step2: マイリストに移動 */
    $response = $this->get('/?tab=mylist&keyword=時計');

    // マイリストでも検索条件が維持されている（腕時計だけ表示）
    $response->assertSee('腕時計');
    $response->assertDontSee('Tシャツ');
}

}
