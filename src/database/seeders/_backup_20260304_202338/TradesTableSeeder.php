<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class TradesTableSeeder extends Seeder
{

    public function run()
    {
        $now = now();
        // item_id=1 を取引中にする（1商品1取引なので tradeは1件）
        DB::table('trades')->insert([
            'id' => 1,
            'item_id' => 1,
            'buyer_id' => 2,
            'seller_id' => 1,
            'status' => 0, // 取引中
            'completed_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // items側も取引中に合わせる（整合性）
        DB::table('items')->where('id', 1)->update([
            'status' => Item::STATUS_TRADING,
            'updated_at' => $now,
        ]);
    }
}
