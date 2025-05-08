<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    //変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    public function test_profile_edit_page_displays_prefilled_user_info()
    {
        $user = User::factory()->create([
            'name'      => 'テスト太郎',
            'image_at'  => 'profile.jpg',
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区1-2-3',
            'building' => 'テストビル101',
        ]);

        $response = $this->actingAs($user)->get(route('mypage.edit'));

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('profile.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('テストビル101');
    }
}
