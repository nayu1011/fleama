<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'マイク',
                'email' => 'seller1@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => $now,
                'image_path' => 'images/profiles/seller1.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'シンディ',
                'email' => 'buyer1@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => $now,
                'image_path' => 'images/profiles/buyer1.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => '紐づき無しユーザー',
                'email' => 'buyer2@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => $now,
                'image_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
