<?php

namespace Tests\Feature\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン済みユーザーはコメントを送信できる()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect("/item/{$item->id}");

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'This is a test comment.',
        ]);

        $response = $this->get("/item/{$item->id}");
        $response -> assertSee('コメント(1)');
    }

    /** @test */
    public function 未ログインユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();
        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => 'This is a test comment.',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'This is a test comment.',
        ]);
        
        $response->assertRedirect("/login");
    }

    /** @test */
    public function コメントが入力されていない場合、バリデーションメッセージを表示する()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

    /** @test */
    public function コメントが255文字を超える場合、バリデーションメッセージを表示する()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors(['comment']);
    }
}
