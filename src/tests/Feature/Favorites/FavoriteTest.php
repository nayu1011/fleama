<?php

namespace Tests\Feature\Favorites;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねアイコン押下でいいね数が1増える()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $item = Item::factory()->create([
            'like_count' => 0,
        ]);

        $this->post("/item/{$item->id}/favorite");

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'like_count' => 1,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response -> assertStatus(200);
        $this -> assertMatchesRegularExpression('/<div class="icon__count">\s*1\s*<\/div>/',$response->getContent());
    }

    /** @test */
    public function いいねアイコン押下でアイコンに色が付く()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);
        $item = Item::factory()->create([
            'like_count' => 0,
        ]);

        $this->post("/item/{$item->id}/favorite");

        $this->get("/item/{$item->id}")
            ->assertStatus(200)
            ->assertSee('fa-heart fa-solid liked');
    }

    /** @test */
    public function いいね解除アイコン押下でいいね数が1減る()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);
        $item = Item::factory()->create([
            'like_count' => 1,
        ]);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->post("/item/{$item->id}/favorite");
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'like_count' => 0,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response -> assertStatus(200);
        $this -> assertMatchesRegularExpression('/<div class="icon__count">\s*0\s*<\/div>/',$response->getContent());
    }

}
