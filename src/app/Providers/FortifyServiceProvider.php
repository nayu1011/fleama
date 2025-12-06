<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\RegisteredResponse;
use App\Http\Responses\LoginResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Fortifyのレスポンス差し替え（登録時）
        $this->app->singleton(RegisterResponse::class, RegisteredResponse::class);

        // ログイン後のレスポンス差し替え
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);

        // ログインリクエストの差し替え
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);        
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        // 会員登録画面のViewを指定
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログイン画面のViewを指定
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // メール認証を必須にする
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}