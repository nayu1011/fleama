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
                'comment' => '素早い発送ありがとうございました！商品もとても良い状態でした。',
            ],
            [
                'user_id' => 2,
                'item_id' => 1,
                'comment' => 'いい買い物ができました。また利用したいです。',
            ],
            [
                'user_id' => 2,
                'item_id' => 3,
                'comment' => '丁寧な梱包で安心しました。また機会があればよろしくお願いします。',
            ],
        ]);
    }
}
