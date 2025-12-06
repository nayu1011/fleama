<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 出品した商品
    public function items()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    // お気に入り
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // お気に入り商品一覧
    public function favoriteItems()
    {
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id');
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 購入した商品
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'buyer_id');
    }

    // 複数配送先
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }}
