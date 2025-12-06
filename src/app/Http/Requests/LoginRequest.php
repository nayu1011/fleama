<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FortifyLoginRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required','email'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスの形式が正しくありません',
            'password.required' => 'パスワードを入力してください',
        ];
    }

    /**
     * 認証失敗（入力情報誤り）時のメッセージ上書き
     */
    protected function throwFailedAuthenticationException()
    {

        $message = 'ログイン情報が登録されていません';

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }
}