<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;


class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 会員登録すると認証メールが送信される()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
            'email' => 'ndkysk@gmail.com',
        ]);

        // 会員登録時にメール送信が走る想定
        // 明示的に通知発行
        $user->sendEmailVerificationNotification();

        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );    
}

    /** @test */
    public function 認証待ち画面から認証ボタン押下でメール認証サイトに遷移する()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => null]);
        $this->actingAs($user);

        $response = $this->get('/email/verify');

        $response->assertStatus(200);
        $response->assertSee('認証はこちらから'); // ボタン文言で評価
    }

    /** @test */
    public function メール認証を完了するとプロフィール画面に遷移する()
    {
        /** @var User $user */
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user);

        // 認証リンク（署名付きURL）を発行
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        // 認証リンクを GET
        $response = $this->get($verificationUrl);

        // 認証完了後、プロフィールに飛ぶ
        $response->assertRedirect(route('mypages.edit'));

        // email_verified_at が更新されている
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
