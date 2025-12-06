<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザー登録画面が表示される()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /** @test */
    public function 名前未入力の場合はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => '', // ← 名前未入力
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function メールアドレス未入力の場合はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'Nayu',
            'email' => '', // ← メール未入力
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが未入力の場合はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'Nayu',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

    }

    /** @test */
    public function パスワード7文字以下の場合はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'Nayu',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワード確認が一致しない場合はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'Nayu',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password_confirmation']);
    }

    /** @test */
    public function ユーザーを新規登録できる()
    {
        $response = $this->post('/register', [
            'name' => 'Nayu',
            'email' => 'nayu@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/mypage/profile');

        $this->assertDatabaseHas('users', [
            'name' => 'Nayu',
            'email' => 'nayu@example.com',
        ]);
    }
}