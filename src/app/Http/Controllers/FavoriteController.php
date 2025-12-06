<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // いいね登録・解除
    public function store($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $favorite = Favorite::where('user_id', $user->id)->where('item_id',$item->id)->first();

        if ($favorite) {
            // いいね済なら解除
            $favorite->delete();

            // 総件数を減らす
            $item->decrement('like_count');
        } else {
            // いいねしていなければ登録
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);

            // 総件数を増やす
            $item->increment('like_count');
        }

        return redirect(route('items.show', ['item_id' => $item_id]));
    }
}
