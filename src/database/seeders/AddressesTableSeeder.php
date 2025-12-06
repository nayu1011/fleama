<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('addresses')->insert([
            [
                'user_id' => 1,
                'postal_code' => '123-4567',
                'address' => '東京都新宿区西新宿1-2-3',
                'building' => '新宿ビルディング101',
            ],
            [
                'user_id' => 2,
                'postal_code' => '234-5678',
                'address' => '東京都渋谷区神山町2-3-4',
                'building' => '渋谷ビル202',
            ],
        ]);
    }
}
