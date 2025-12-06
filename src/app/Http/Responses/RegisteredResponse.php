<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisteredResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // 会員登録直後はプロフィール登録画面へ遷移
        return redirect()->route('mypages.edit');
    }
}
