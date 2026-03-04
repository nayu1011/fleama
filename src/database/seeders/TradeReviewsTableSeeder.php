<?php

namespace Database\Seeders;

use App\Models\Trade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradeReviewsTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $rows = Trade::query()
            ->where('status', Trade::STATUS_COMPLETED)
            ->get(['id', 'buyer_id', 'seller_id'])
            ->flatMap(function (Trade $trade) use ($now) {
                return [
                    [
                        'trade_id' => $trade->id,
                        'reviewer_id' => $trade->buyer_id,
                        'reviewee_id' => $trade->seller_id,
                        'rating' => 5,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'trade_id' => $trade->id,
                        'reviewer_id' => $trade->seller_id,
                        'reviewee_id' => $trade->buyer_id,
                        'rating' => 5,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ];
            })
            ->all();

        if (empty($rows)) {
            return;
        }

        DB::table('trade_reviews')->upsert(
            $rows,
            ['trade_id', 'reviewer_id'],
            ['reviewee_id', 'rating', 'updated_at']
        );
    }
}
