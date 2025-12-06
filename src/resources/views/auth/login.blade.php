@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-page">
        <h2 class="page-heading">ログイン</h2>
        <form class="login-page__form" action="{{ route('login') }}" method="POST" novalidate>
            <div class="login-page__form-inner">
                @csrf
                <div class="form__group">
                    <label class="form__label" for="email">メールアドレス</label>
                    <input class="form__input" type="email" name="email" id="email">
                    @error('email')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form__group">
                    <label class="form__label" for="password">パスワード</label>
                    <input class="form__input" type="password" name="password" id="password">
                    @error('password')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
                <input class="form__btn" type="submit" value="ログインする">
        </form>
        <a class="page-link" href="/register">会員登録はこちら</a>
    </div>
@endsection
