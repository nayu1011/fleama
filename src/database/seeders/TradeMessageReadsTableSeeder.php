<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradeMessageReadsTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        DB::table('trade_message_reads')->insert([
            [
                'id' => 1,
                'trade_id' => 1,
                'user_id' => 2, // buyerは既読
                'last_read_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'trade_id' => 1,
                'user_id' => 1, // sellerは一部未読（buyerの直近1件を未読）
                'last_read_at' => $now->copy()->subMinutes(13),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'trade_id' => 2,
                'user_id' => 2, // buyerは既読
                'last_read_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'trade_id' => 2,
                'user_id' => 1, // sellerはbuyerの最新1件を未読
                'last_read_at' => $now->copy()->subMinutes(2),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'trade_id' => 3,
                'user_id' => 2, // buyerは既読
                'last_read_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'trade_id' => 3,
                'user_id' => 1, // sellerは未読2件
                'last_read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
