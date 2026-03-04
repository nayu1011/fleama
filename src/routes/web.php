<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TradeController;

/*
|--------------------------------------------------------------------------
| 公開ルート（認証不要）
|--------------------------------------------------------------------------
*/
//PG03 会員登録画面はFortifyを使用
//PG04 ログイン画面はFortifyを使用

Route::get('/',[ItemController::class,'index'])->name('items.index');   //PG01 商品一覧画面（トップ画面）、PG02 商品一覧画面
Route::get('/item/{item_id}',[ItemController::class,'show'])->name('items.show');   //PG05 商品詳細画面

// stripe 決済結果画面(決済完了)
Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchases.success');

// stripe 決済結果画面(キャンセル)
Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');

/*
|--------------------------------------------------------------------------
| メール認証関連（Fortify標準）
|--------------------------------------------------------------------------
*/
// メール認証待ち画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

// メール確認（リンククリック時）
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

    // メールアドレスを「認証済み」にする
    $request->fulfill();

    // 認証直後はプロフィール編集画面へ
    return redirect()->route('mypages.edit');

})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

  // 認証メール再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


/*
|--------------------------------------------------------------------------
| 認証＋メール認証済ユーザーのみアクセス可
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','verified'])->group(function () {
    // マイページ関連
    Route::get('/mypage',[MypageController::class,'index'])->name('mypages.index'); //PG09 プロフィール画面、PG11 プロフィール画面_購入した商品一覧、PG12 プロフィール画面_出品した商品一覧
    Route::get('/mypage/profile',[MypageController::class,'edit'])->name('mypages.edit');   //PG10 プロフィール編集画面（設定画面）
    Route::post('/mypage/profile',[MypageController::class,'update'])->name('mypages.update');  //PG10 プロフィール編集画面（設定画面）

    // いいね関連
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');

    // コメント投稿機能
    Route::post('/item/{item_id}/comments', [CommentController::class,'store'])->name('comments.store'); //PG05 商品詳細画面

    // 出品関連
    Route::get('/sell',[SellController::class,'create'])->name('sells.create');  //PG08 商品出品画面
    Route::post('/sell',[SellController::class,'store'])->name('sells.store');   //PG08 商品出品画面

    // 購入関連
    Route::get('/purchase/address/{item_id}',[PurchaseController::class,'editAddress'])->name('purchases.editAddress'); //PG07 送付先住所変更画面
    Route::post('/purchase/address/{item_id}',[PurchaseController::class,'updateAddress'])->name('purchases.updateAddress'); //PG07 送付先住所変更画面

    Route::get('/purchase/{item_id}',[PurchaseController::class,'create'])->name('purchases.create');   //PG06 購入確認画面
    Route::post('/purchase/{item_id}',[PurchaseController::class,'store'])->name('purchases.store');    //PG06 商品購入画面

    // 取引関連
    Route::get('/trade/{trade}', [TradeController::class, 'show'])->name('trades.show');
    Route::post('/trade/{trade}/message', [TradeController::class, 'store'])->name('trades.storeMessage');
    Route::patch('/trade/messages/{message}', [TradeController::class, 'update'])->name('trades.updateMessage');
    Route::delete('/trade/messages/{message}', [TradeController::class, 'destroy'])->name('trades.destroyMessage');

    Route::post('/trade/{trade}/review', [TradeController::class, 'review'])->name('trades.review');
});
