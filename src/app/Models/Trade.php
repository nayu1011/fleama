<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trade extends Model
{
    use HasFactory;

    const STATUS_TRADING = 0; // 取引中
    const STATUS_BUYER_COMPLETED = 1; // 購入者が取引完了
    const STATUS_COMPLETED = 2; // 取引完了

    protected $fillable = [
        'item_id',
        'buyer_id',
        'seller_id',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TradeMessage::class, 'trade_id');
    }

    public function tradeMessageReads(): HasMany
    {
        return $this->hasMany(TradeMessageRead::class, 'trade_id');
    }

    public function tradeReviews(): HasMany
    {
        return $this->hasMany(TradeReview::class, 'trade_id');
    }
}
