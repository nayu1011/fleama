<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Item;
use App\Models\Purchase;

class MypageController extends Controller
{
    // プロフィール・マイページ表示
    public function index(Request $request)
    {
        // 認証チェック
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'sell') {
            $items = $user->items()->paginate(12);
        } elseif ($page === 'buy') {
            $purchasedItemIds = Purchase::where('buyer_id', $user->id)->pluck('item_id');
            $items = Item::whereIn('id', $purchasedItemIds)->paginate(12);
        } else {
            $items = collect([]);
        }

        return view('mypages.index', compact('user', 'page', 'items'));
    }

    // プロフィール設定（編集）画面
    public function edit()
    {
        $user = Auth::user();
        $address = $user->addresses()->first();

        return view('mypages.edit', compact('user', 'address'));
    }

    // プロフィール更新処理
    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        $oldImage = $user->image_path;
        // 画像アップロード処理
        if ($request->hasFile('image')) {
            // 古い画像があれば削除
            if ($oldImage && Storage::exists($oldImage)) {
                Storage::delete($oldImage);
            }

            $path = $request->file('image')->store('images/profiles', 'public');
            $user->image_path = $path;
        }

        // ユーザー情報更新
        $user->name = $request->input('name');
        $user->save();

        // 住所情報更新
        $user->addresses()->updateOrCreate(
            [],$request->only(['postal_code','address','building']) 
        );

        return redirect()->route('mypages.index');
    }
}
