<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    // ログイン後のレスポンス
    public function toResponse($request)
    {
        $user = $request->user();

        // メール未認証なら verify 画面へ
        if ($user && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // 認証済なら通常の遷移
        return redirect()->intended('/');
    }
}
