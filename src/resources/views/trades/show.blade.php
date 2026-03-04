@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trades/show.css') }}">
@endsection

@section('content')
@php
    $userId = auth()->id();
    $isBuyer = $userId === $trade->buyer_id;
    $partner = $isBuyer ? $trade->seller : $trade->buyer;
@endphp

<div class="trade-page">
    {{-- Sidebar --}}
    <aside class="trade-sidebar">
        <h2 class="trade-sidebar-title">その他の取引</h2>

        <div class="trade-sidebar-list">
            @foreach($otherTrades as $otherTrade)
                <a href="{{ route('trades.show', $otherTrade) }}" class="trade-sidebar-item">
                    {{ $otherTrade->item->name ?? '商品名なし' }}
                </a>
            @endforeach
        </div>
    </aside>

    <main class="trade-main">
        {{-- Header --}}
        <header class="trade-header">
            <div class="trade-header-left">
                <div class="avatar avatar-lg">
                    {{-- 画像がない場合は丸だけでもOK。運用するならimg差し替え --}}
                    @if(!empty($partner->image_path))
                        <img src="{{ Storage::url($partner->image_path) }}">
                    @endif
                </div>
                <h1 class="trade-header-title">「{{ $partner->name }}」さんとの取引画面</h1>
            </div>

            {{-- 購入者のみ：取引完了ボタン --}}
            @if($isBuyer && $canReview)
                <div class="trade-header-right">
                    <button type="button" class="btn btn-complete js-open-review-modal">取引を完了する</button>
                </div>
            @endif
        </header>

        {{-- Item card --}}
        @include('trades.partials.itemCard', ['item' => $trade->item])

        {{-- Messages --}}
        <section class="trade-messages">
            @include('trades.partials.messages', [
                'messages' => $trade->messages,
                'userId' => $userId,
            ])
        </section>

        {{-- Form --}}
        <div class="trade-form-area">
            @include('trades.partials.messageForm', ['trade' => $trade])
        </div>
    </main>
</div>

@if($canReview)
    <div class="trade-review-modal" id="trade-review-modal" aria-hidden="true">
        <div class="trade-review-modal-backdrop js-close-review-modal"></div>
        <div class="trade-review-modal-panel" role="dialog" aria-modal="true" aria-labelledby="trade-review-title">
            <div class="trade-review-modal-head">
                <h2 id="trade-review-title">取引が完了しました。</h2>
            </div>

            <form method="POST" action="{{ route('trades.review', $trade) }}">
                @csrf

                <div class="trade-review-modal-body">
                    <p class="trade-review-modal-question">今回の取引相手はどうでしたか？</p>

                    <div class="trade-review-modal-stars">
                        @for($i = 5; $i >= 1; $i--)
                            <input id="trade-rating-{{ $i }}" type="radio" name="rating" value="{{ $i }}" @checked((int) old('rating') === $i)>
                            <label for="trade-rating-{{ $i }}">★</label>
                        @endfor
                    </div>

                    @error('rating')
                        <p class="trade-review-modal-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="trade-review-modal-foot">
                    <button type="submit" class="trade-review-modal-submit">送信する</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('trade-review-modal');
        if (!modal) return;

        const openBtn = document.querySelector('.js-open-review-modal');
        const closeTargets = modal.querySelectorAll('.js-close-review-modal');
        const hasRatingError = {{ $errors->has('rating') ? 'true' : 'false' }};
        const autoOpenReviewModal = {{ $autoOpenReviewModal ? 'true' : 'false' }};

        const openModal = function () {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
        };

        const closeModal = function () {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        };

        if (openBtn) {
            openBtn.addEventListener('click', openModal);
        }

        closeTargets.forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });

        if (hasRatingError || autoOpenReviewModal) {
            openModal();
        }
    });
    </script>
@endif
@endsection
