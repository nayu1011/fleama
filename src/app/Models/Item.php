<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // 商品ステータス定数
    const STATUS_BEFORE_LISTING = 0;   // 出品前
    const STATUS_LISTING       = 1;   // 出品中
    const STATUS_TRADING       = 2;   // 取引中
    const STATUS_SOLD          = 3;   // 売却済み
    const STATUS_WITHDRAWN     = 8;   // 退会未売却
    const STATUS_PRIVATE       = 9;   // 運営非公開
    
    // 商品状態定数
    const CONDITIONS = [
        0 => '新品',
        1 => '目立った傷や汚れなし',
        2 => 'やや傷や汚れあり',
        3 => '状態が悪い',
    ];

    protected $fillable = [
        'seller_id',
        'name',
        'brand_name',
        'description',
        'price',
        'condition',
        'image_path',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function isFavoritedBy($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
