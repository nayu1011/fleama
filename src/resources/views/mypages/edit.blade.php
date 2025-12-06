@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypages/edit.css') }}">
@endsection

@section('js')
    <script type="module" src="{{ asset('js/profile-image.js') }}"></script>
@endsection

@section('content')
    <div class="profile-edit-page">
        <h2 class="page-heading">プロフィール設定</h2>
        <form class="profile-edit-page__form" action="{{ route('mypages.update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="profile-edit-page__form-inner">
                <div class="form__group form__group-image">
                    <div class="profile__image-wrapper">
                        <img class="profile__image {{ $user->image_path ? '' : 'is-hidden' }}"
                            src="{{ $user->image_path ? Storage::url($user->image_path) : '' }}" id="preview">
                        @if (!$user->image_path)
                            <div class="profile__no-image" id="no-image">画像未設定</div>
                        @endif
                    </div>
                    <label class="image-upload-btn" for="file">画像を選択する</label>
                    <input class="image-upload-input" type="file" name="image" id="file" accept="image/*">
                    @error('image')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form__group">
                    <label class="form__label" for="name">ユーザー名</label>
                    <input class="form__input" type="text" name="name" id="name"
                        value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form__group">
                    <label class="form__label" for="postal_code">郵便番号</label>
                    <input class="form__input" type="text" name="postal_code" id="postal_code"
                        value="{{ old('postal_code', $address->postal_code ?? '') }}">
                    @error('postal_code')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form__group">
                    <label class="form__label" for="address">住所</label>
                    <input class="form__input" type="text" name="address" id="address"
                        value="{{ old('address', $address->address ?? '') }}">
                    @error('address')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form__group">
                    <label class="form__label" for="building">建物名</label>
                    <input class="form__input" type="text" name="building" id="building"
                        value="{{ old('building', $address->building ?? '') }}">
                    @error('building')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <input class="form__btn" type="submit" value="更新する">
            </div>
        </form>
    </div>
@endsection
