<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{

    use RefreshDatabase;
    /** @test */
    public function ログアウトできる()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);
        $response = $this->post('/logout');

        $response->assertRedirect('/'); // ログアウト後のリダイレクト先を確認
        $this->assertGuest(); // ユーザーがログアウトしていることを確認
    }
}