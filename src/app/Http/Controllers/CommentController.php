<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        if(Auth::check())
        {
            // ログイン済みユーザーのみコメント登録
            $item = Item::findOrFail($item_id);

            $item->comments()->create([
                'user_id' => Auth::id(),
                'comment' => $request->comment,
            ]);
        }

        // 商品詳細画面へリダイレクト
        return redirect(route('items.show', ['item_id' => $item_id]));
    }
}
