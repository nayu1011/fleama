@php
    use App\Models\Item;
@endphp

@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mypages/index.css') }}">
@endsection

@section('content')
    <div class="mypage-page">
        {{-- ユーザー情報エリア --}}
        <div class="user-info">
            <div class="profile__image-wrapper">
                <img class="profile__image" src="{{ Storage::url($user->image_path) }}">
            </div>
            <p class="user-info__name">{{ $user->name }}</p>
            <a class="user-info__profile-edit" href="{{ route('mypages.edit') }}">プロフィールを編集</a>
        </div>

        {{-- タブメニュー --}}
        <div class="tabs">
            <div class="tabs-inner">
                    <a class="tabs__link {{ $page === 'sell' ? 'tabs__link--active' : '' }}"
                        href="{{ route('mypages.index', ['page' => 'sell', 'keyword' => $keyword ?? '']) }}">出品した商品</a>
                    <a class="tabs__link {{ $page === 'buy' ? 'tabs__link--active' : '' }}"
                        href="{{ route('mypages.index', ['page' => 'buy', 'keyword' => $keyword ?? '']) }}">購入した商品</a>
            </div>
        </div>

        {{-- 商品リストエリア --}}
        <div class="item-list__area">
            @foreach ($items as $item)
                <div class="item__card">
                    <a class="item__link" href="{{ route('items.show', ['item_id' => $item->id]) }}">
                        <div class="item__image-wrapper">
                            <img class="item__image" src="{{ Storage::url($item->image_path) }}" alt="商品画像">
                            @if ($item->status === Item::STATUS_SOLD)
                                <img class="sold-badge" src="{{ asset('images/sold.png') }}">
                            @endif
                        </div>
                        <div class="item__name">{{ $item->name }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
