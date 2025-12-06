<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase; // ← テストごとにDBをリセット
    
    /** @test */
    public function ログイン画面が表示される()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function メールアドレス未入力の場合はバリデーションエラーになる()
    {
        $response = $this->post('/login', [
            'email' => '', // ← メール未入力
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワード未入力の場合はバリデーションエラーになる()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 登録されていないメールアドレスの場合はバリデーションエラーになる()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが間違っている場合はバリデーションエラーになる()
    {
        // 事前にユーザーを作成
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct_password'),
        ]);
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function 正しい情報が入力された場合は正常にログインできる()
    {
        // 事前にユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct_password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'correct_password',
        ]);

        // ログイン成功の確認
        $response->assertRedirect('/'); // ログイン後のリダイレクト先を確認
        $this->assertAuthenticatedAs($user); // ユーザーが認証されていることを確認
    }
}