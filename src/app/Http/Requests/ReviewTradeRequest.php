<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewTradeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rating' => 'required|integer|between:1,5', // 評価は必須、1から5の整数
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価は必須です（1～5）。',
        ];
    }
}
