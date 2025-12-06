<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    const STATUS_PROCESSING = 0; // 処理中
    const STATUS_AWAITING_PAYMENT = 1; // 支払い待ち
    const STATUS_PAID = 2;    // 支払済み
    const STATUS_AWAITING_SHIPMENT = 3; // 発送待ち
    const STATUS_SHIPPED = 4; // 発送済み
    const STATUS_DELIVERED = 5; // 配達済み
    const STATUS_RECEIVED = 6; // 受取済み
    const STATUS_COMPLETED = 7; // 完了
    const STATUS_CANCELLED_BY_BUYER = 8; // 購入者によるキャンセル
    const STATUS_CANCELLED_BY_SELLER = 9; // 出品者によるキャンセル

    const PAYMENT_CONVENIENCE_STORE = 1; // コンビニ支払い
    const PAYMENT_CREDIT_CARD = 2; // クレジットカード

    protected $fillable = [
        'item_id',
        'buyer_id',
        'postal_code',
        'address',
        'building',
        'payment_method',
        'total_price',
        'status',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
