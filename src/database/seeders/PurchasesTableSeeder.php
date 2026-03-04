<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchasesTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $buyerId = 2;
        $buyerAddress = DB::table('addresses')->where('user_id', $buyerId)->first();

        DB::table('purchases')->upsert([
            [
                'item_id' => 1,
                'buyer_id' => $buyerId,
                'postal_code' => $buyerAddress->postal_code ?? '',
                'address' => $buyerAddress->address ?? '',
                'building' => $buyerAddress->building ?? '',
                'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
                'total_price' => 15000,
                'status' => Purchase::STATUS_PAID,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'item_id' => 2,
                'buyer_id' => $buyerId,
                'postal_code' => $buyerAddress->postal_code ?? '',
                'address' => $buyerAddress->address ?? '',
                'building' => $buyerAddress->building ?? '',
                'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
                'total_price' => 5000,
                'status' => Purchase::STATUS_PAID,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'item_id' => 3,
                'buyer_id' => $buyerId,
                'postal_code' => $buyerAddress->postal_code ?? '',
                'address' => $buyerAddress->address ?? '',
                'building' => $buyerAddress->building ?? '',
                'payment_method' => Purchase::PAYMENT_CREDIT_CARD,
                'total_price' => 300,
                'status' => Purchase::STATUS_PAID,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['item_id'], [
            'buyer_id',
            'postal_code',
            'address',
            'building',
            'payment_method',
            'total_price',
            'status',
            'updated_at',
        ]);
    }
}
