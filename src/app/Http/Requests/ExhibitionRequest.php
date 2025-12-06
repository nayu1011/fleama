<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'], // 商品名
            'description' => ['required','max:255'], // 商品説明
            'image' => ['required','image','mimes:jpeg,png'], // 商品画像
            'categories' => ['required','array','min:1'], // 複数カテゴリ対応
            'categories.*' => ['exists:categories,id'], // ID存在チェック
            'condition' => ['required'], // 商品の状態
            'price' => ['required','integer','min:0'], // 価格
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '商品画像は画像ファイルを選択してください',
            'image.mimes' => '商品画像はjpegまたはpng形式の画像を選択してください',
            'categories.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は整数で入力してください',
            'price.min' => '価格は0円以上で入力してください',
        ];
    }
}
