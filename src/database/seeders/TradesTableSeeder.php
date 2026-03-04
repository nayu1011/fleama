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
        DB::table('trades')->insert([
            [
                'id' => 1,
                'item_id' => 1,
                'buyer_id' => 2,
                'seller_id' => 1,
                'status' => 0, // 取引中
                'completed_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'item_id' => 2,
                'buyer_id' => 2,
                'seller_id' => 1,
                'status' => 0, // 取引中
                'completed_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'item_id' => 3,
                'buyer_id' => 2,
                'seller_id' => 1,
                'status' => 0, // 取引中
                'completed_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('items')->whereIn('id', [1, 2, 3])->update([
            'status' => Item::STATUS_TRADING,
            'updated_at' => $now,
        ]);
    }
}
