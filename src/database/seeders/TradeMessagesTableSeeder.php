<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradeMessagesTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        DB::table('trade_messages')->insert([
            [
                'trade_id' => 1,
                'sender_id' => 2, // buyer
                'message' => '購入しました。よろしくお願いします！',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(16),
                'updated_at' => $now->copy()->subMinutes(16),
            ],
            [
                'trade_id' => 1,
                'sender_id' => 1, // seller
                'message' => 'ありがとうございます！本日発送予定です。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(12),
                'updated_at' => $now->copy()->subMinutes(12),
            ],
            [
                'trade_id' => 1,
                'sender_id' => 2, // buyer
                'message' => '早速ありがとうございます！楽しみにしています。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(8),
                'updated_at' => $now->copy()->subMinutes(8),
            ],
            [
                'trade_id' => 2,
                'sender_id' => 2, // buyer
                'message' => 'こちらも購入しました。確認をお願いします。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(6),
                'updated_at' => $now->copy()->subMinutes(6),
            ],
            [
                'trade_id' => 2,
                'sender_id' => 1, // seller
                'message' => '承知しました。明日までに発送します。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(4),
                'updated_at' => $now->copy()->subMinutes(4),
            ],
            [
                'trade_id' => 2,
                'sender_id' => 2, // buyer（trade 2 が最新）
                'message' => 'ありがとうございます。よろしくお願いします！',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(1),
                'updated_at' => $now->copy()->subMinutes(1),
            ],
            [
                'trade_id' => 3,
                'sender_id' => 2, // buyer
                'message' => 'こちらの商品も購入しました。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(14),
                'updated_at' => $now->copy()->subMinutes(14),
            ],
            [
                'trade_id' => 3,
                'sender_id' => 2, // buyer（未読件数を作るため buyer 連投）
                'message' => '発送予定日を教えてください。',
                'image_path' => null,
                'deleted_at' => null,
                'created_at' => $now->copy()->subMinutes(3),
                'updated_at' => $now->copy()->subMinutes(3),
            ],
        ]);
    }
}
