<?php

namespace Tests\Feature\Items;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品一覧画面が表示される()
    {
        Item::factory()->count(3)->state(new Sequence(
            ['name' => 'テスト商品A'],
            ['name' => 'テスト商品B'],
            ['name' => 'テスト商品C']
        ))->create();

        $response = $this->get('/');

        $response->assertSee('テスト商品A');
        $response->assertSee('テスト商品B');
        $response->assertSee('テスト商品C');
    }

    /** @test */
    public function 購入済み商品は「sold」と表示される()
    {
        $item = Item::factory()->create([
            'name' => '購入済み商品',
            'status' => Item::STATUS_SOLD,
        ]);

        $response = $this->get('/');
        $response->assertSee('sold-badge');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        
        $this->actingAs($user);
        $item = Item::factory()->create(['seller_id' => $user->id]);

        $response = $this->get('/');

        $response->assertDontSee($item->name);
    }


}
