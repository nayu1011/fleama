<?php

namespace Tests\Feature\Mypages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function マイページに必要な情報（プロフィール画像、ユーザー名、出品した商品）が表示される()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'image_path' => 'test_image.jpg',
            'email_verified_at' => now()
        ]);
        $this->actingAs($user);

        $items = Item::factory()->count(2)->create(['seller_id' => $user->id]);

        $response = $this->get(route('mypages.index'));
        $response->assertStatus(200)
            ->assertSee('テストユーザー')
            ->assertSee('test_image.jpg')
            ->assertSee($items[0]->name)
            ->assertSee($items[1]->name);
    }

        /** @test */
    public function マイページに購入した商品が表示される()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'image_path' => 'test_image.jpg',
            'email_verified_at' => now()
        ]);
        $this->actingAs($user);

        $purchasedItems = Purchase::factory()->count(3)->create(['buyer_id' => $user->id]);

        $response = $this->get(route('mypages.index'),['page' => 'buy']);
        $response->assertStatus(200)
            ->assertSee('テストユーザー')
            ->assertSee('test_image.jpg')
            ->assertSee($purchasedItems[0]->name)
            ->assertSee($purchasedItems[1]->name)
            ->assertSee($purchasedItems[2]->name);
    }

    /** @test */
    public function プロフィール変更画面に初期設定情報が表示される()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'image_path' => 'test_image.jpg',
            'email_verified_at' => now(), 
        ]);
        $this->actingAs($user);

        Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都杉並区阿佐ヶ谷1-2-3',
            'building' => 'テストビル909',
        ]);

        $response = $this->get(route('mypages.edit'));
        $response->assertStatus(200)
            ->assertSee('test_image.jpg')
            ->assertSee('テストユーザー')
            ->assertSee('123-4567')
            ->assertSee('東京都杉並区阿佐ヶ谷1-2-3')
            ->assertSee('テストビル909');
    }

}