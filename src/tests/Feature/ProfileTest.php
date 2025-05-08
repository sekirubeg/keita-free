<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    
    //必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_profile_page_displays_user_info_and_items()
    {
        // ユーザー作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'image_at' => 'test_profile.jpg',
        ]);
        Payment::factory()->create(['id' => 1, 'payment_method' => 'クレジットカード']);
        // 出品した商品
        Item::factory()->create([
            'name' => '出品商品',
            'user_id' => $user->id,
        ]);

        // 別の商品を購入したレコードを作成
        $anotherItem = Item::factory()->create(['name' => '購入商品']);
        Order::factory()->create([
            'user_id'    => $user->id,
            'item_id'    => $anotherItem->id,
            'post_code'  => '123-4567',
            'address'    => '東京都渋谷区テスト1-2-3',
            'building'   => 'テストビル101',
            'price'      => 1234,
            'payment_id' => 1, // 適切な支払い方法ID
        ]);

        // ログインしてプロフィールページにアクセス
        $response = $this->actingAs($user)->get(route('mypage'));

        // 検証
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');     // ユーザー名
        $response->assertSee('test_profile.jpg');    // プロフィール画像
        $response->assertSee('出品商品');           // 出品商品

        $response = $this->actingAs($user)->get(route('mypage', ['page' => 'buy']));
        $response->assertSee('購入商品');           // 購入商品
    }
}
