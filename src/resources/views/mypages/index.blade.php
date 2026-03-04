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
            <div class="user-info__profile">
                <p class="user-info__name">{{ $user->name }}</p>
                @if(($roundedRating ?? 0) > 0)
                    <div class="user-rating" aria-label="平均評価 {{ $roundedRating }}/5">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="user-rating__star {{ $i <= $roundedRating ? 'is-active' : '' }}">★</span>
                        @endfor
                    </div>
                @endif
            </div>
            <a class="user-info__profile-edit" href="{{ route('mypages.edit') }}">プロフィールを編集</a>
        </div>

        {{-- タブメニュー --}}
        <div class="tabs">
            <div class="tabs-inner">
                    <a class="tabs__link {{ $page === 'sell' ? 'tabs__link--active' : '' }}"
                        href="{{ route('mypages.index', ['page' => 'sell', 'keyword' => $keyword ?? '']) }}">出品した商品</a>
                    <a class="tabs__link {{ $page === 'buy' ? 'tabs__link--active' : '' }}"
                        href="{{ route('mypages.index', ['page' => 'buy', 'keyword' => $keyword ?? '']) }}">購入した商品</a>
                    <a class="tabs__link {{ $page === 'trade' ? 'tabs__link--active' : '' }}"
                        href="{{ route('mypages.index', ['page' => 'trade', 'keyword' => $keyword ?? '']) }}">
                        取引中の商品
                        @if(($tradeUnreadTotal ?? 0) >0)
                            <span class="tabs__unread-badge">{{ $tradeUnreadTotal }}</span>
                        @endif
                    </a>
            </div>
        </div>

        {{-- 商品リストエリア --}}
        <div class="item-list__area">
            @foreach ($items as $item)
                <div class="item__card">
                    <a
                        class="item__link"
                        href="{{ $page === 'trade'
                            ? route('trades.show', ['trade' => $item->trade?->id])
                            : route('items.show', ['item_id' => $item->id])
                        }}"
                    >
                        <div class="item__image-wrapper">
                            <img class="item__image" src="{{ Storage::url($item->image_path) }}" alt="商品画像">
                            @if (in_array($item->status, [Item::STATUS_TRADING, Item::STATUS_SOLD], true))
                                <img class="sold-badge" src="{{ asset('images/sold.png') }}">
                            @endif
                            @if ($page === 'trade' && ($item->unread_count ?? 0) > 0)
                                <div class="item__unread-badge">{{ $item->unread_count }}</div>
                            @endif
                        </div>
                        <div class="item__name">{{ $item->name }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
