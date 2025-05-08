<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_purchase_creates_order_and_redirects()
    {
        $user = User::factory()->create([
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);

        // Paymentデータを事前に作成
        \App\Models\Payment::create([
            'id' => 1,
            'payment_method' => 'クレジットカード',
        ]);

        $item = \App\Models\Item::factory()->create([
            'price' => 4500,
        ]);

        $this->actingAs($user);

        session([
            'purchase_payment_id' => 1,
            'purchase_post_code' => '111-2222',
            'purchase_address' => '大阪市中央区',
            'purchase_building' => 'なんばビル202',
        ]);

        $response = $this->get(route('item.purchase.success', ['id' => $item->id]));

        $response->assertRedirect('/');
        $response->assertSessionHas('success', '購入が完了しました。');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price' => 4500,
            'payment_id' => 1,
            'post_code' => '111-2222',
            'address' => '大阪市中央区',
            'building' => 'なんばビル202',
        ]);
    }
    public function test_purchased_item_displays_sold_label_in_list()
    {
        $this->withoutExceptionHandling();

        // ユーザーと商品を作成
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create();

        // 支払い方法を登録（payment_idが1などになるように）
        $payment = \App\Models\Payment::firstOrCreate(['payment_method' => 'クレジットカード']);

        // 商品を購入（ordersテーブルに登録）
        \App\Models\Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'payment_id' => $payment->id,
            'post_code' => '123-4567',
            'address' => '東京都千代田区1-1',
            'building' => 'テストビル',
        ]);

        // 商品一覧ページを表示
        $response = $this->get('/');

        // "Sold" ラベルが表示されていることを確認
        $response->assertSee('Sold');
    }
    public function test_purchased_item_appears_in_profile_purchased_list()
    {
        $this->withoutExceptionHandling();

        // ユーザーと商品を作成
        $user = \App\Models\User::factory()->create([
            'image_at' => 'https://example.com/dummy.jpg',
        ]);
        $item = \App\Models\Item::factory()->create();

        // 支払い方法を用意（既に存在していれば再利用）
        $payment = \App\Models\Payment::firstOrCreate(['payment_method' => 'クレジットカード']);

        // 注文データを作成（購入処理）
        \App\Models\Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'payment_id' => $payment->id,
            'post_code' => '100-0001',
            'address' => '東京都港区',
            'building' => 'テストマンション202',
        ]);

        // ログインしてプロフィール画面にアクセス
        $response = $this->actingAs($user)->get('/mypage?page=buy');

        // 商品名が表示されていればOK
        $response->assertSee($item->name);
    }
}