<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            ['id' => 1,
            'name' => '出品者１',
            'email' => 'seller1@example.com',
            'password' => bcrypt('passwords1'),
            'email_verified_at' => now(),
            'image_path' => 'images/profiles/seller1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            ['id' => 2,
            'name' => '購入者１',
            'email' => 'buyer1@example.com',
            'password' => bcrypt('passwordb1'),
            'email_verified_at' => now(),
            'image_path' => 'images/profiles/buyer1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
