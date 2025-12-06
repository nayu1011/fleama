<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use Illuminate\Container\Container;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        //RegisterRequestでバリデーション
        $request = new RegisterRequest();
        $request->merge($input);

        $request->setContainer(Container::getInstance())->setRedirector(app(Redirector::class));

        $request->validateResolved();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // 登録直後にログインさせる
        Auth::login($user);

        // 作成したユーザーを返す
        return $user;
    }
}
