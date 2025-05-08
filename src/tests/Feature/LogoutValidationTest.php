<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutValidationTest extends TestCase
{
    public function test_user_can_logout()
    {
        // ログイン済みのユーザーを作成・ログインさせる
        $user = \App\Models\User::factory()->create([
            'email' => 'logout_' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user); // ユーザーをログイン状態にする

        // POSTでログアウトを実行（通常LaravelはPOST /logout）
        $response = $this->post('/logout');

        // ログアウト後は未認証状態であることを確認
        $this->assertGuest();

        // ログイン画面などにリダイレクトされる（ルートに応じて変更）
        $response->assertRedirect('/');
    }
}
