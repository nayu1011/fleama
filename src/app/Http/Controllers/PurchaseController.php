<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create(Request $request, $item_id)
    {
        // 認証チェック
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        /* 住所は、初回はDB、2回目以降はsession */
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


    public function editAddress($item_id)
    {
        // 認証チェック
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $item = Item::findOrFail($item_id);
        $address = session('address');

        return view('purchases.editAddress', compact('item', 'address'));
    }


    public function updateAddress(AddressRequest $request, $item_id)
    {
        // 認証チェック
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        session([
            'address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ],
        ]);

        return redirect()->route('purchases.create', $item_id);
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        // 認証チェック
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $item = Item::findOrFail($item_id);
        $address = session ('address');
        $payment_method = session('payment_method');

        // セッションに住所情報がない場合は購入確認画面にリダイレクト
        if (!$address) {
            return redirect()->route('purchases.create', $item_id);
        }

        // 購入情報をDBに保存する
        $purchase = new Purchase();
        $purchase->item_id = $item->id;
        $purchase->buyer_id = Auth::id();
        $purchase->postal_code = $address['postal_code'] ?? '';
        $purchase->address = $address['address'] ?? '';
        $purchase->building = $address['building'] ?? '';
        $purchase->payment_method = $payment_method;
        $purchase->total_price = $item->price;
        $purchase->status = Purchase::STATUS_PAID; // 支払済み
        $purchase->save();

        // 商品のステータスを「売却済み」に更新
        $item->status = Item::STATUS_SOLD; // 売却済み
        $item->save();

        // セッション情報をクリア
        session()->forget(['address', 'payment_method']);

        // Stripe未連携の場合のリダイレクト
        // return redirect()->route('items.index');

        // テスト環境の場合はStripe決済をスキップ
        if (app()->environment('testing')) {
            return redirect()->route('purchases.success');
        }

        // Stripe決済セッションを作成
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
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
            'success_url' => route('purchases.success'),
            'cancel_url' => route('purchases.cancel'),
        ]);

        return redirect($session->url);
    }
}
