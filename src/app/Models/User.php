<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image_path',
        'rating_average',
        'rating_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 出品した商品
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    // お気に入り
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // お気に入り商品一覧
    public function favoriteItems(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id');
    }

    // コメント
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // 購入した商品
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'buyer_id');
    }

    // 複数配送先
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    // 取引レビュー（レビュアー）
    public function givenReviews(): HasMany
    {
        return $this->hasMany(TradeReview::class, 'reviewer_id');
    }

    // 取引レビュー（レビュイー）
    public function receivedReviews(): HasMany
    {
        return $this->hasMany(TradeReview::class, 'reviewee_id');
    }

    // 取引メッセージ
    public function tradeMessages(): HasMany
    {
        return $this->hasMany(TradeMessage::class, 'sender_id');
    }

    // 取引メッセージの既読情報
    public function tradeMessageReads(): HasMany
    {
        return $this->hasMany(TradeMessageRead::class, 'user_id');
    }

    // 取引（購入者）
    public function buyingTrades(): HasMany
    {
        return $this->hasMany(Trade::class, 'buyer_id');
    }

    // 取引（販売者）
    public function sellingTrades(): HasMany
    {
        return $this->hasMany(Trade::class, 'seller_id');
    }
}
