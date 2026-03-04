<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            AddressesTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemsTableSeeder::class,
            PurchasesTableSeeder::class,
            FavoritesTableSeeder::class,
            CommentsTableSeeder::class,
            CategoryItemTableSeeder::class,

            TradesTableSeeder::class,
            TradeMessagesTableSeeder::class,
            TradeMessageReadsTableSeeder::class,
            TradeReviewsTableSeeder::class,
        ]);
    }
}
