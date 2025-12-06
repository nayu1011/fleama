<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                  => ['required', 'string', 'max:20'],
            'email'                 => ['required', 'email', 'unique:users,email'], // usersテーブルのemailカラム重複不可
            'password'              => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'                 => 'お名前を入力してください',
            'email.required'                => 'メールアドレスを入力してください',
            'email.email'                   => 'メールアドレスはメール形式で入力してください',
            'email.unique'                  => 'そのメールアドレスは既に登録されています',
            'password.required'             => 'パスワードを入力してください',
            'password.min'                  => 'パスワードは8文字以上で入力してください',
            'password_confirmation.required'=> 'パスワードを入力してください',
            'password_confirmation.min'     => 'パスワードは8文字以上で入力してください',
            'password_confirmation.same'    => 'パスワードと一致しません',
        ];
    }
}
