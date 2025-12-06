@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}"> {{-- 商品情報 --}}
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}"> {{-- 商品情報 --}}
    <link rel="stylesheet" href="{{ asset('css/sells/create.css') }}">
@endsection

@section('js')
    <script type="module" src="{{ asset('js/sell-image.js') }}"></script>
@endsection

@section('content')
    <div class="sell-page">

        {{-- ページタイトル --}}
        <h2 class="page-heading ">商品の出品</h2>
        <form class="sell-form" action="{{ route('sells.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="sell-form__inner">

                {{-- 商品画像アップロード --}}
                <div class="form__group">
                    <label class="form__label">商品画像</label>
                    <div class="sell__image-area">
                        <div class="sell__image-wrapper is-hidden" id="preview-wrapper">
                            <img class="sell__image" src="" id="preview">
                        </div>
                        <label class="image-upload-btn" for="file">画像を選択する</label>
                        <input class="image-upload-input" type="file" name="image" id="file" accept="image/*">
                    </div>
                    @error('image')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 商品情報入力 --}}
                {{-- 商品の詳細 --}}
                <div class="form__group">
                    <h3 class="form__group-title">商品の詳細</h3>
                    <div class="form__group">
                        {{-- カテゴリー（複数選択可） --}}
                        <label class="form__label">カテゴリー</label>
                        @foreach ($categories as $category)
                            <label class="category-item">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                        @error('categories')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form__group">
                        {{-- 商品の状態 --}}
                        <label class="form__label" for="condition">商品の状態</label>
                        <div class="form__select-wrapper">
                            <select class="form__select" name="condition" id="condition">
                                <option value="">選択してください</option>
                                @foreach ($conditions as $value => $condition)
                                    <option value="{{ $value }}"
                                        {{ old('condition') === (string) $value ? 'selected' : '' }}>
                                        {{ $condition }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('condition')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 商品名と説明 --}}
                <div class="form__group">
                    <h3 class="form__group-title">商品名と説明</h3>
                    <div class="form__group">
                        <label class="form__label" for="name">商品名</label>
                        <input class="form__input" type="text" name="name" id="name"
                            value="{{ old('name') }}">
                        @error('name')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="brand_name">ブランド名</label>
                        <input class="form__input" type="text" name="brand_name" id="brand_name"
                            value="{{ old('brand_name') }}">
                        @error('brand_name')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form__group">
                        <label class="form__label" for="description">商品の説明</label>
                        <textarea class="form__textarea" name="description" id="description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form__group price-input-wrapper">
                        <label class="form__label" for="price">販売価格</label>
                        <input class="form__input" type="number" name="price" id="price"
                            value="{{ old('price') }}">
                        @error('price')
                            <p class="form__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 出品ボタン --}}
                <input class="form__btn" type="submit" value="出品する">
            </div>
        </form>
    </div>
@endsection
