<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('comments')->insert([
            [
                'user_id' => 2,
                'item_id' => 1,
                'comment' => '値下げはいくらまで可能でしょうか。',
            ],
            [
                'user_id' => 1,
                'item_id' => 1,
                'comment' => 'こちらの商品は値下げ不可となっております。ご了承ください。',
            ],
            [
                'user_id' => 2,
                'item_id' => 1,
                'comment' => '承知しました。ご回答ありがとうございます。',
            ],
            [
                'user_id' => 1,
                'item_id' => 10,
                'comment' => '他の角度からの写真もみせていただけますでしょうか。',
            ],
            [
                'user_id' => 2,
                'item_id' => 10,
                'comment' => '写真を追加しました。ご確認ください。',
            ],
            [
                'user_id' => 1,
                'item_id' => 10,
                'comment' => 'ありがとうございます。検討させていただきます。',
            ],
        ]);
    }
}
