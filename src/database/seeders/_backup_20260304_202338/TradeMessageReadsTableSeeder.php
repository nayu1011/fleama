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
                'user_id' => 1, // sellerは未読（バッジ表示用）
                'last_read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
