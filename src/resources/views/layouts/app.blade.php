<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    @yield('js')
    <title>coachtechフリマ</title>
</head>

<body>
    <div class="app">
        <header class="header">

            <div class="header__logo-wrapper">
                <a href="{{ route('items.index') }}">
                    <img class="header__logo header__logo--desktop" src="{{ asset('images/COACHTECH_logo.png') }}" alt="COACHTECH">
                    <img class="header__logo header__logo--tablet" src="{{ asset('images/CT_logo.png') }}" alt="COACHTECH">
                </a>
            </div>

            <form class="header__search-form" action="{{ route('items.index') }}" method="GET">
                <input class="header__search-input" type="text" name="keyword" id="keyword"
                    placeholder="なにをお探しですか？" value="{{ $keyword ?? '' }}">
            </form>

            <div class="header__link-inner">
                @if (Auth::check())
                    <form class="header__link-logout header__link" action="{{ route('logout') }}" method="POST">
                        @csrf
                            <input class="header__link-logout" type="submit" value="ログアウト">
                    </form>
                @else
                    <a class="header__link-login" href="{{route('login')}}">ログイン</a>
                @endif
                <a class="header__link-mypage header__link" href="{{ route('mypages.index') }}">マイページ</a>
                <a class="header__link-sell header__link" href="{{ route('sells.create') }}">出品</a>
            </div>
        </header>

        <main class="content">
            @yield('content')
        </main>
    </div>
</body>

</html>
