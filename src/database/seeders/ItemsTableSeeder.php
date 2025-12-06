<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('items')->insert([
            [
                'seller_id' => 1,
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => 0,
                'image_path' => 'images/items/ArmaniMensClock.jpg',
                'like_count' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'name' => 'HDD',
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => 1,
                'image_path' => 'images/items/HDDHardDisk.jpg',
                'like_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'name' => '玉ねぎ3束',
                'brand_name' => '',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 2,
                'image_path' => 'images/items/iLoveIMGd.jpg',
                'like_count' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'name' => '革靴',
                'brand_name' => null,
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,    
                'condition' => 3,
                'image_path' => 'images/items/LeatherShoesProductPhoto.jpg',
                'like_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'name' => 'ノートPC',
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => 0,
                'image_path' => 'images/items/LivingRoomLaptop.jpg',
                'like_count' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'name' => 'マイク',
                'brand_name' => '',
                'description' => <<<TEXT
                    高音質のレコーディング用マイク！
                    ボーカルや楽器の録音に最適です。
                    コンデンサーマイクでクリアな音質を実現。
                    ポップフィルターとショックマウントも付属しています。
                    自宅スタジオや配信におすすめのアイテムです。
                    この機会にぜひどうぞ！今ならお得な価格で提供中！
                    TEXT,
                'price' => 8000,
                'condition' => 1,
                'image_path' => 'images/items/MusicMic4632231.jpg',
                'like_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 2,
                'name' => 'ショルダーバッグ',
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 2,
                'image_path' => 'images/items/Pursefashionpocket.jpg',
                'like_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'name' => 'タンブラー',
                'brand_name' => '',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => 3,
                'image_path' => 'images/items/Tumblersouvenir.jpg',
                'like_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => 0,
                'image_path' => 'images/items/WaitresswithCoffeeGrinder.jpg',
                'like_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 2,
                'name' => 'メイクセット',
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => 1,
                'image_path' => 'images/items/外出メイクアップセット.jpg',
                'like_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
