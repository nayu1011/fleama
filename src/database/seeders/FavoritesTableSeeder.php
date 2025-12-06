<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoritesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('favorites')->insert([
            [
                'user_id' => 2,
                'item_id' => 1,
            ],
            [
                'user_id' => 2,
                'item_id' => 3,
            ],
            [
                'user_id' => 2,
                'item_id' => 5,
            ],
            [
                'user_id' => 1,
                'item_id' => 5,
            ],
            [
                'user_id' => 1,
                'item_id' => 10,
            ],
            [
                'user_id' => 1,
                'item_id' => 6,
            ],  
            [
                'user_id' => 1,
                'item_id' => 1,
            ],
        ]);
    }
}
