<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    // 商品一覧表示
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        // 検索キーワード取得
        $keyword = trim($request->input('keyword', ''));

        $query = Item::query();

        // 自分が出品した商品を除外（ログイン時のみ）
        if (Auth::check()) {
            $query->where('seller_id', '!=', Auth::id());
        }        

        // キーワード検索時（検索対象は商品名のみ）
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', "%$keyword%");
        }

        // マイリスト選択時
        if ($tab === 'mylist') {
            // 未認証なら空のコレクションで返す
            if (!Auth::check()) {
                return view('items.index',['items' => collect([]), 'tab' => $tab, 'keyword' => $keyword]);
            } 

            $query->whereIn('id', Auth::user()->favoriteItems()->pluck('items.id'));
        }

        $items = $query->paginate(12);

        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    // 商品詳細表示
    public function show($item_id)
    {
        $item = Item::with([
            'seller',
            'categories',
            'comments.user',
            'favorites'
        ])->findOrFail($item_id);

        $isFavorited = false;

        if (Auth::check()) {
            $isFavorited = $item->favorites()->where('user_id', Auth::id())->exists();
        }

        return view('items.show', compact('item', 'isFavorited'));
    }
}
