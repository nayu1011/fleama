<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewTradeRequest;
use App\Mail\TradeCompletedReviewedMail;
use App\Models\Trade;
use App\Models\TradeMessage;
use App\Models\TradeMessageRead;
use App\Models\TradeReview;
use App\Models\User;
use App\Models\Item;
use App\Http\Requests\TradeRequest;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TradeController extends Controller
{
    /**
     * 取引詳細（メッセージ一覧）
     */
    public function show(Trade $trade)
    {
        $userId = Auth::id();
        $openedAt = now();
        $isBuyer = $userId === $trade->buyer_id;

        if (!$this->canAccessTrade($trade, $userId)) {
            abort(403);
        }

        // 既読レコードがなければ作る（堅め）
        $read = TradeMessageRead::firstOrCreate(
            ['trade_id' => $trade->id, 'user_id' => $userId],
            ['last_read_at' => null]
        );

        // 取引・メッセージを取得（必要十分に eager load）
        $trade->load([
            'item',
            'buyer:id,name,image_path',
            'seller:id,name,image_path',
            'messages' => function ($messageQuery) {
                $messageQuery->with('sender:id,name,image_path')->orderBy('created_at');
            },
        ]);

        // その他の取引
        $otherTrades = Trade::query()
        ->where(function ($participantQuery) use ($userId) {
            $participantQuery->where('buyer_id', $userId)->orWhere('seller_id', $userId);
        })
        ->whereKeyNot($trade->id)
        ->leftJoin(DB::raw('(SELECT trade_id, MAX(created_at) AS last_message_at FROM trade_messages GROUP BY trade_id) tm_last'), 'tm_last.trade_id', '=', 'trades.id')
        ->orderByDesc(DB::raw('COALESCE(tm_last.last_message_at, trades.created_at)'))
        ->with('item:id,name,image_path,price')
        ->select('trades.*')
        ->take(20)
        ->get();

        // 未読件数（自分が送った分は除外）
        $unreadCount = $trade->messages()
            ->where('sender_id', '!=', $userId)
            ->when($read->last_read_at, function ($messageQuery) use ($read) {
                $messageQuery->where('created_at', '>', $read->last_read_at);
            })
            ->where('created_at', '<=', $openedAt) // 画面を開いた時点まで
            ->count();

        // 画面を開いたら「既読」にする（last_read_atを更新）
        $read->update(['last_read_at' => $openedAt]);

        $hasReviewed = TradeReview::query()
            ->where('trade_id', $trade->id)
            ->where('reviewer_id', $userId)
            ->exists();

        $canReview = !$hasReviewed && (
            $isBuyer
                || in_array($trade->status, [Trade::STATUS_BUYER_COMPLETED, Trade::STATUS_COMPLETED], true)
        );

        $autoOpenReviewModal = !$isBuyer && $canReview;

        return view('trades.show', compact('trade', 'unreadCount', 'otherTrades', 'canReview', 'autoOpenReviewModal'));
    }

    /**
     * メッセージ送信
     */
    public function store(TradeRequest $request, Trade $trade)
    {
        $userId = Auth::id();

        if (!$this->canAccessTrade($trade, $userId)) {
            abort(403);
        }

        // バリデーション済みのデータを取得
        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/trades', 'public');
        }

        DB::transaction(function () use ($trade, $userId, $validated, $imagePath) {
            TradeMessage::create([
                'trade_id' => $trade->id,
                'sender_id' => $userId,
                'message' => $validated['message'] ?? null,
                'image_path' => $imagePath,
            ]);

            // 送信者は既読扱い（今開いて送ってるので）
            TradeMessageRead::updateOrCreate(
                ['trade_id' => $trade->id, 'user_id' => $userId],
                ['last_read_at' => now()]
            );

            $otherUserId = ($userId === $trade->buyer_id) ? $trade->seller_id : $trade->buyer_id;

            TradeMessageRead::firstOrCreate(
                ['trade_id' => $trade->id, 'user_id' => $otherUserId],
                ['last_read_at' => null]
            );
        });

        return redirect()->route('trades.show', $trade);
    }

    /**
     * buyer/seller判定
     */
    private function canAccessTrade(Trade $trade, int $userId): bool
    {
        return $trade->buyer_id === $userId || $trade->seller_id === $userId;
    }

    // 取引レビュー送信（購入者 -> 販売者）
    public function review(ReviewTradeRequest $request, Trade $trade)
    {
        $userId = Auth::id();

        if (!$this->canAccessTrade($trade, $userId)) {
            abort(403);
        }

        $isBuyer = $trade->buyer_id === $userId;
        $isSeller = $trade->seller_id === $userId;

        if (!$isBuyer && !$isSeller) {
            abort(403);
        }

        if ($isSeller && !in_array($trade->status, [Trade::STATUS_BUYER_COMPLETED, Trade::STATUS_COMPLETED], true)) {
            abort(403);
        }

        $validated = $request->validated();
        DB::transaction(function () use ($trade, $userId, $validated, $isBuyer, $isSeller) {
            if ($isBuyer) {
                $this->markBuyerCompleted($trade);
            }

            $revieweeId = $isBuyer ? $trade->seller_id : $trade->buyer_id;

            TradeReview::updateOrCreate(
                [
                    'trade_id' => $trade->id,
                    'reviewer_id' => $userId,
                ],
                [
                    'reviewee_id' => $revieweeId,
                    'rating' => $validated['rating'],
                ]
            );

            $ratingStats = TradeReview::query()
                ->where('reviewee_id', $revieweeId)
                ->selectRaw('COUNT(*) as review_count, AVG(rating) as avg_rating')
                ->first();

            User::whereKey($revieweeId)->update([
                'rating_count' => (int) ($ratingStats->review_count ?? 0),
                'rating_average' => isset($ratingStats->avg_rating) ? round((float) $ratingStats->avg_rating, 2) : null,
            ]);

            if ($isSeller && $trade->status === Trade::STATUS_BUYER_COMPLETED) {
                $trade->update([
                    'status' => Trade::STATUS_COMPLETED,
                    'completed_at' => now(),
                ]);
            }
        });

        if ($isBuyer) {
            $trade->loadMissing([
                'item:id,name',
                'buyer:id,name',
                'seller:id,name,email',
            ]);

            Mail::to($trade->seller->email)->send(
                new TradeCompletedReviewedMail($trade, (int) $validated['rating'], route('trades.show', $trade))
            );
        }

        return redirect()->route('items.index');
    }

    // メッセージ編集
    public function update(TradeRequest $request, TradeMessage $message)
    {
        $userId = Auth::id();

        // 自分のメッセージ以外編集不可
        if ($message->sender_id !== $userId) {
            abort(403);
        }

        $validated = $request->validated();

        $message->update([
            'message' => $validated['message'] ?? $message->message,
        ]);

        return redirect()->route('trades.show', $message->trade_id);
    }

    // メッセージ削除
    public function destroy(TradeMessage $message)
    {
        $userId = Auth::id();

        if ($message->sender_id !== $userId) {
            abort(403);
        }

        $tradeId = $message->trade_id;
        $message->delete();

        return redirect()->route('trades.show', $tradeId);
    }

    private function markBuyerCompleted(Trade $trade): void
    {
        $trade->loadMissing(['item.purchase']);

        if ($trade->status === Trade::STATUS_COMPLETED) {
            return;
        }

        $trade->update([
            'status' => Trade::STATUS_BUYER_COMPLETED
        ]);

        if ($trade->item) {
            $trade->item->update(['status' => Item::STATUS_SOLD]);

            if ($trade->item->purchase) {
                $trade->item->purchase->update(['status' => Purchase::STATUS_COMPLETED]);
            }
        }
    }
}
