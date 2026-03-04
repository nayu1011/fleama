<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Trade;
use App\Models\TradeMessageRead;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    // 購入確認画面表示
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /* 住所は、初回はDB、2回目以降はsessionの値を使う */
        $sessionAddress = session('address');
        if (!$sessionAddress) {
            $dbAddress = $user->addresses()->first();
            $sessionAddress = [
                'postal_code' => $dbAddress->postal_code ?? '',
                'address'     => $dbAddress->address ?? '',
                'building'    => $dbAddress->building ?? '',
            ];
            session(['address' => $sessionAddress]);
        }

        /* 支払方法を GETで受け取ったら更新 */
        if ($request->filled('payment_method')) {
            session(['payment_method' => $request->payment_method]);
        }

        $selectedPayment = session('payment_method', '');

        return view('purchases.create', compact('item', 'selectedPayment', 'sessionAddress'));    }


    // 住所編集画面表示
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $address = session('address');

        return view('purchases.editAddress', compact('item', 'address'));
    }

    // 住所更新処理
    public function updateAddress(AddressRequest $request, $item_id)
    {
        session([
            'address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ],
        ]);

        return redirect()->route('purchases.create', $item_id);
    }

    // 購入処理
    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $address = session ('address');
        session(['payment_method' => $request->payment_method]);

        // セッションに住所情報がない場合は購入確認画面にリダイレクト
        if (!$address) {
            return redirect()->route('purchases.create', $item_id);
        }

        // テスト環境の場合はStripe決済をスキップ
        if (app()->environment('testing')) {
            session(['purchased_item_id' => $item->id]);
            return redirect()->route('purchases.success');
        }

        // Stripe決済セッションを作成
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            // success側で item を復元するために item_id を metadata に入れる
            'metadata' => [
                'item_id' => (string)$item->id,
            ],
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('purchases.success').'?session_id={CHECKOUT_SESSION_ID}', // session_idをクエリパラメータで渡す
            'cancel_url' => route('purchases.cancel'),
        ]);

        return redirect($session->url);
    }

    //Stripe決済完了：paidを確認してからDBに保存
    public function success(Request $request)
    {
        if (app()->environment('testing')) {
            // テスト環境の場合はセッションからitem_idを取得して処理をスキップ
            $itemId = session('purchased_item_id');
            if (!$itemId) {
                return redirect()->route('items.index');
            }
            $session = (object)[
                'metadata' => (object)['item_id' => (string)$itemId],
                'payment_status' => 'paid',
            ];
        } else {
            $sessionId = $request->query('session_id');
            if (!$sessionId) {
                return redirect()->route('items.index');
            }

            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                $session = Session::retrieve($sessionId);
            } catch (\Exception $exception) {
                return redirect()->route('items.index');
            }
        }

        // 決済が成功しているか確認
        if (($session->payment_status ?? null) !== 'paid') {
            return redirect()->route('purchases.cancel');
        }

        // item_idはmetadataから取得
        $itemId = $session->metadata->item_id ?? null;
        if (!$itemId) {
            return redirect()->route('items.index');
        }

        $item = Item::findOrFail($itemId);

        //二重決済ガード
        if (
            in_array($item->status, [Item::STATUS_TRADING, Item::STATUS_SOLD], true) ||
            Trade::where('item_id', $item->id)->exists()
        ) {
            return redirect()->route('items.index');
        }

        $address = session('address');
        $payment_method = session('payment_method');
        if (!$address) {
            return redirect()->route('purchases.create', $item->id);
        }

        // トランザクション開始
        DB::transaction(function () use ($item, $address, $payment_method){
            // 購入確定
            Purchase::create([
                'item_id' => $item->id,
                'buyer_id' => Auth::id(),
                'postal_code' => $address['postal_code'] ?? '',
                'address' => $address['address'] ?? '',
                'building' => $address['building'] ?? '',
                'payment_method' => $payment_method,
                'total_price' => $item->price,
                'status' => Purchase::STATUS_PAID, // 支払済み
            ]);

            // 取引開始
            $trade = Trade::create([
                'item_id' => $item->id,
                'buyer_id' => Auth::id(),
                'seller_id' => $item->seller_id,
                'status' => Trade::STATUS_TRADING, // 取引中
                'completed_at' => null,
            ]);


            // 商品のステータスを「取引中」に更新
            $item->update(['status' => Item::STATUS_TRADING]); // 取引中

            // 既読テーブルに追加（出品者は未読）
            // 購入者用
            TradeMessageRead::create([
                'trade_id' => $trade->id,
                'user_id' => $trade->buyer_id,
                'last_read_at' => now(), // 購入者は既読
            ]);

            // 出品者用
            TradeMessageRead::create([
                'trade_id' => $trade->id,
                'user_id' => $trade->seller_id,
                'last_read_at' => null,
            ]);
        });

        session()->forget(['address', 'payment_method']);
        return view('purchases.success');
    }

    public function cancel()
    {
        return view('purchases.cancel');
    }
}
