<?php

namespace Tests\Feature\Sells;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出品した商品情報が正しく保存される()
    {

        // 画像のアップロードを偽装
        Storage::fake('public');

        /** @var User $seller */
        $seller = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($seller);

        // カテゴリ作成
        $category = Category::factory()->create();

        $response = $this->post(route('sells.store'), [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト商品の説明です。',
            'price' => 5000,
            'categories' => [$category->id],
            'condition' => array_rand(Item::CONDITIONS),
            'image' => UploadedFile::fake()->image('test_image.jpg'),
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 5000,
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category->id,
        ]);

        Storage::disk('public')->assertExists(Item::first()->image_path);
    }
}
