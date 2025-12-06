@php
    use App\Models\Item;
@endphp

@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <div class="item-list-page">
        {{-- タブメニュー --}}
        <div class="tabs">
            <div class="tabs-inner">
                <a class="tabs__link {{ $tab !== 'mylist' ? 'tabs__link--active' : '' }}"
                    href="{{ route('items.index', ['keyword' => $keyword ?? '']) }}">おすすめ</a>
                <a class="tabs__link {{ $tab === 'mylist' ? 'tabs__link--active' : '' }}"
                    href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => $keyword ?? '']) }}">マイリスト</a>
            </div>
        </div>

        {{-- 商品リストエリア --}}
        <div class="item-list__area">
            @if ($items->isEmpty())
                @if (Auth::check())
                    <p class="item-list__no-items">マイリストに商品がありません。</p>
                @endif
            @else
                @foreach ($items as $item)
                    <div class="item__card">
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="item__link">
                            <div class="item__image-wrapper">
                                <img class="item__image" src="{{ Storage::url($item->image_path) }}" alt="商品画像">
                                @if ($item->status === Item::STATUS_SOLD)
                                    <img src="{{ asset('images/sold.png') }}" class="sold-badge">
                                @endif
                            </div>
                            <div class="item__name">{{ $item->name }}</div>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
