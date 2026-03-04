<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Trade;
use App\Models\TradeMessage;

class MypageController extends Controller
{
    // プロフィール・マイページ表示
    public function index(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $userId = $user->id;
        $page = $request->query('page', 'sell');
        $tradeUnreadTotal = 0;
        $roundedRating = ((int) ($user->rating_count ?? 0) > 0)
            ? (int) round((float) $user->rating_average)
            : 0;

        if ($page === 'sell') {
            $items = $user->items()->paginate(12);

        } elseif ($page === 'buy') {
            $purchasedItemIds = Purchase::where('buyer_id', $userId)->pluck('item_id');
            $items = Item::whereIn('id', $purchasedItemIds)->paginate(12);

        } elseif ($page === 'trade') {

            $items = Item::query()
                ->whereHas('trade', function ($tradeQuery) use ($userId) {
                    $tradeQuery->where(function ($roleQuery) use ($userId) {
                        $roleQuery->where(function ($buyerQuery) use ($userId) {
                            $buyerQuery->where('buyer_id', $userId)
                                ->where('status', Trade::STATUS_TRADING);
                        })->orWhere(function ($sellerQuery) use ($userId) {
                            $sellerQuery->where('seller_id', $userId)
                                ->whereIn('status', [Trade::STATUS_TRADING, Trade::STATUS_BUYER_COMPLETED]);
                        });
                    });
                })
                ->with([
                    'trade:id,item_id,buyer_id,seller_id',
                    'trade.tradeMessageReads' => function ($tradeReadQuery) use ($userId) {
                        $tradeReadQuery->select('trade_id', 'user_id', 'last_read_at')
                        ->where('user_id', $userId);
                    },
                ])
                ->leftJoin('trades', 'trades.item_id', '=', 'items.id')
                ->leftJoin(DB::raw('(SELECT trade_id, MAX(created_at) AS last_message_at FROM trade_messages GROUP BY trade_id) tm_last'), 'tm_last.trade_id', '=', 'trades.id')
                ->orderByDesc(DB::raw('COALESCE(tm_last.last_message_at, trades.created_at)'))
                ->select('items.*')
                ->paginate(12);

            foreach ($items as $item) {
                $trade = $item->trade;
                if (!$trade) { $item->unread_count = 0; continue; }

                $lastReadAt = optional($trade->tradeMessageReads->first())->last_read_at;

                $item->unread_count = $trade->messages()
                    ->where('sender_id', '!=', $userId)
                    ->when($lastReadAt, fn ($messageQuery) => $messageQuery->where('created_at', '>', $lastReadAt))
                    ->count();
            }
        } else {
            $items = collect([]);
        }

        // 取引中タブの未読合計数
        $tradeUnreadTotal = TradeMessage::query()
            ->join('trades', 'trade_messages.trade_id', '=', 'trades.id')
            ->leftJoin('trade_message_reads as tmr', function ($join) use ($userId) {
                $join->on('tmr.trade_id', '=', 'trade_messages.trade_id')
                    ->where('tmr.user_id', '=', $userId);
            })
            ->where(function ($tradeQuery) use ($userId) {
                $tradeQuery->where(function ($buyerQuery) use ($userId) {
                    $buyerQuery->where('trades.buyer_id', $userId)
                        ->where('trades.status', Trade::STATUS_TRADING);
                })->orWhere(function ($sellerQuery) use ($userId) {
                    $sellerQuery->where('trades.seller_id', $userId)
                        ->whereIn('trades.status', [Trade::STATUS_TRADING, Trade::STATUS_BUYER_COMPLETED]);
                });
            })
            ->where('trade_messages.sender_id', '!=', $userId)
            ->where(function ($readConditionQuery) {
                $readConditionQuery->whereNull('tmr.last_read_at')
                ->orWhereColumn('trade_messages.created_at', '>', 'tmr.last_read_at');
            })
            ->count();

        return view('mypages.index', compact('user', 'page', 'items', 'tradeUnreadTotal', 'roundedRating'));
    }

    // プロフィール設定（編集）画面
    public function edit()
    {
        /** @var \App\Models\User $user **/
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
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
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
