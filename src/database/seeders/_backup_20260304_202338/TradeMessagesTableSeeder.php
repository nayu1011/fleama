<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class TradeMessagesTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $itemImagePath = Item::where('id', 1)->value('image_path'); // 商品画像のパスを取得
        DB::table('trade_messages')->insert([
            [
                'trade_id' => 1,
                'sender_id' => 2, // buyer
                'message' => '購入しました。よろしくお願いします！',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(10),
                'updated_at' => $now->copy()->subMinutes(10),
            ],
            [
                'trade_id' => 1,
                'sender_id' => 1, // seller
                'message' => 'ありがとうございます！本日発送予定です。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(5),
                'updated_at' => $now->copy()->subMinutes(5),
            ],
            [
                'trade_id' => 1,
                'sender_id' => 2, // buyer
                'message' => '早速ありがとうございます！楽しみにしています。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(1),
                'updated_at' => $now->copy()->subMinutes(1),
            ],
        ]);
    }
}
