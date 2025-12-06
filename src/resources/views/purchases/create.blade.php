@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">       {{--商品情報--}}
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">        {{--商品情報--}}
    <link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
    <div class="purchase-page container">
        {{-- 購入情報エリア --}}
        <div class="purchase-info">

            {{-- 商品情報 --}}
            <div class="item-info">
                <div class="item__image-wrapper">
                    <img class="item__image" src="{{ Storage::url($item->image_path) }}" alt="商品画像">
                </div>
                <div class="item-info-detail">
                    <h2 class="item-name">{{ $item->name }}</h2>
                    <p class="item-price">¥ {{ number_format($item->price) }}</p>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="payment-method">
                <h2 class="payment-method__title">支払い方法</h2>
                <form class="payment-method-form" action="{{ route('purchases.create', $item->id) }}" method="GET" id="payment-method-form">
                    <div class="form__select-wrapper">
                        {{-- 支払方法選択時にセッション保存のため送信 --}}
                        <select class="payment-method__select form__select"
                                name="payment_method"
                                onchange="document.getElementById('payment-method-form').submit()">
                            <option value="">選択してください</option>
                            <option value="1" {{ $selectedPayment == 1 ? 'selected' : '' }}>コンビニ支払い</option>
                            <option value="2" {{ $selectedPayment == 2 ? 'selected' : '' }}>クレジットカード</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- 配送先情報 --}}
            <div class="shipping-info">
                <div class="shipping-header">
                    <h2 class="shipping__title">配送先</h2>
                    <a class="page-link" href="{{ route('purchases.editAddress', $item->id) }}">変更する</a>
                </div>
                <div class="shipping__body">
                    <p class="shipping__row">〒{{ session('address.postal_code') }}</p>
                    <p class="shipping__row">
                        {{ session('address.address') }}
                        @if (session('address.building'))
                            {{ session('address.building') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- 支払い情報エリア --}}
        <div class="payment-info">

            {{-- 支払い情報グループ --}}
            <div class="payment-info-group-wrapper">
                <div class="payment-info-group">
                    <div class="payment-info__label">商品代金</div>
                    <div class="payment-info__value">¥{{ number_format($item->price) }}</div>
                </div>
                <div class="payment-info-group">
                    <div class="payment-info__label">支払い方法</div>
                    <div class="payment-info__value">
                        {{ $selectedPayment == 1 ? 'コンビニ支払い' : ($selectedPayment == 2 ? 'クレジットカード' : '選択してください') }}
                    </div>
                </div>
            </div>
            @error('payment_method')
                <p class="form__error">{{ $message }}</p>
            @enderror

            {{-- 購入ボタン --}}
            <form action="{{ route('purchases.store', $item->id) }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method" value="{{ $selectedPayment }}">
                <input class="form__btn" type="submit" value="購入する">
            </form>
        </div>
    </div>
@endsection
