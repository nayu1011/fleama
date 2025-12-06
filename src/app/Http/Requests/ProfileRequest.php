<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'image'         => ['nullable', 'image', 'mimes:jpeg,png'], // プロフィール画像
            'name'          => ['required', 'string', 'max:20'], // ユーザー名
            'postal_code'   => ['required', 'regex:/^\d{3}-\d{4}$/'], // 郵便番号
            'address'       => ['required'], // 住所
            'building'      => ['nullable'], // 建物名
        ];
    }

    public function messages()
    {
        return [
            'image.image'           => 'プロフィール画像は画像ファイルを選択してください',
            'image.mimes'           => 'プロフィール画像はjpegまたはpng形式の画像を選択してください',
            'name.required'         => 'ユーザー名を入力してください',
            'name.string'           => 'ユーザー名は文字列で入力してください',
            'name.max'              => 'ユーザー名は20文字以内で入力してください',
            'postal_code.required'  => '郵便番号を入力してください',
            'postal_code.regex'     => '郵便番号はハイフンありの8文字で入力してください　例：123-4567',
            'address.required'      => '住所を入力してください',
        ];
    }
}
