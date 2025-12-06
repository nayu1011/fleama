<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;

class SellController extends Controller
{
    // 出品画面表示
    public function create()
    {
        $categories = Category::all();
        $conditions = Item::CONDITIONS;
        return view('sells.create', compact('categories', 'conditions'));
    }

    // 出品処理
    public function store(ExhibitionRequest $request)
    {
        // 画像アップロード
        $imagePath = $request->file('image')->store('images/items', 'public');

        // Item 作成
        $item = Item::create([
            'seller_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'brand_name' => $request->brand_name,
            'condition' => $request->condition,
            'price' => $request->price,
            'image_path' => $imagePath,
            'status' => Item::STATUS_LISTING, // 出品中に設定
        ]);

        // カテゴリ紐付け（多対多）
        $item->categories()->sync($request->input('categories', []));

        return redirect()->route('items.index');
    }
}
