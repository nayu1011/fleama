@php
    use App\Models\Item;
@endphp

@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="item-detail-page">
            {{-- 商品画像エリア --}}
            <div class="item-image-area">
                <div class="item-image-wrapper">
                    <img class="item-image" src="{{ Storage::url($item->image_path) }}" alt="商品画像">
                    @if ($item->status === Item::STATUS_SOLD)
                        <img src="{{ asset('images/sold.png') }}" class="sold-badge">
                    @endif
                </div>
            </div>
            {{-- 商品詳細エリア --}}
            <div class="item-detail-area">
                {{-- 商品名 --}}
                <h1 class="item-name">{{ $item->name }}</h1>
                {{-- ブランド名 --}}
                <p class="item-brand-name">{{ $item->brand_name }}</p>
                {{-- 価格 --}}
                <p class="item-price">¥{{ number_format($item->price) }} (税込)</p>
                <div class="icon-area">
                    <div class="icon-group">
                        {{-- いいねアイコン 認証ユーザーのみいいね可 --}}
                        @if (Auth::check())
                            <form class="favorite-form" action="{{ route('favorite.store', $item->id) }}" method="POST">
                                @csrf
                                <button class="favorite-form__btn icon" type="submit">
                                    <i
                                        class="fa-heart {{ $item->isFavoritedBy(Auth::user()) ? 'fa-solid liked' : 'fa-regular' }}"></i>
                                </button>
                            </form>
                        @else
                            <div class="icon">
                                <i class="fa-regular fa-heart"></i>
                            </div>
                        @endif
                        {{-- いいね数 --}}
                        <div class="icon__count">{{ $item->like_count }}</div>
                    </div>
                    {{-- コメントアイコン --}}
                    <div class="icon-group">
                        <div class="icon">
                            <i class="fa-regular fa-comment"></i>
                        </div>
                        {{-- コメント数 --}}
                        <div class="icon__count">{{ $item->comments->count() }}</div>
                    </div>
                </div>
                {{-- 購入ボタン --}}
                @if (Auth::check() && $item->status !== Item::STATUS_SOLD)
                    <form class="purchase-form" action="{{ route('purchases.create', $item->id) }}" method="GET">
                        @csrf
                        <input class="form__btn purchase-form__btn" type="submit" value="購入手続きへ">
                    </form>
                @else
                    <button type="button" class="form__btn form__btn--disabled">購入手続きへ</button>
                @endif
                {{-- 商品説明エリア --}}
                <div class="item-description-area">
                    <h2 class="item-description-title">商品説明</h2>
                    <p class="item-description">{!! nl2br(e($item->description)) !!}</p>
                </div>
                <div class="item-info-area">
                    <h2 class="item-description-title">商品の情報</h2>
                    <div class="item-info-row">
                        <div class="item-info-label">カテゴリー</div>
                        <div class="item-info-value">
                            @forelse ($item->categories as $category)
                                <div class="item-category">{{ $category->name }}</div>
                            @empty
                                未設定
                            @endforelse
                        </div>
                    </div>
                    <div class="item-info-row">
                        <div class="item-info-label">商品の状態</div>
                        <div class="item-info-value">{{ Item::CONDITIONS[$item->condition] ?? '未設定' }}</div>
                    </div>
                </div>
                {{-- コメントエリア --}}
                <div class="comment-area">
                    <h2 class="comment-title">コメント({{ $item->comments->count() }})</h2>
                    {{-- 既存のコメント表示 --}}
                    @foreach ($item->comments as $comment)
                        <div class="comment__item">
                            <div class="comment__user">
                                <div class="image-profile-wrapper">
                                    @if ($comment->user->image_path)
                                        <img class="image-profile" src="{{ Storage::url($comment->user->image_path) }}">
                                    @endif
                                </div>
                                <div class="comment__user-name">{{ $comment->user->name }}</div>
                            </div>
                            <p class="comment__body">{!! nl2br(e($comment->comment)) !!}</p>
                        </div>
                    @endforeach
                    {{-- コメント入力フォーム --}}
                    <form class="comment-form" action="{{ route('comments.store', ['item_id' => $item->id]) }}"
                        method="POST">
                        @csrf
                        <h3 class="comment-input-title">商品へのコメント</h3>
                        <textarea class="comment-textarea" name="comment" id="comment" rows="4" placeholder="コメントを入力してください">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="comment-error form__error">{{ $message }}</p>
                        @enderror
                        @if (Auth::check())
                            {{-- ログインユーザーのみ投稿可 --}}
                            <input class="form__btn" type="submit" value="コメントを送信する">
                        @else
                            <button class="form__btn form__btn--disabled" type="button">コメントを送信する</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
