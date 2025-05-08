<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;


class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_products_are_displayed()
    {
        // テスト用に3件の商品を作成
        $items = Item::factory()->count(3)->create();

        // 商品一覧ページへアクセス
        $response = $this->get('/');

        // ステータスコード確認
        $response->assertStatus(200);

        // 各商品名が表示されていることを確認
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }
    public function test_sold_label_is_displayed_when_item_is_ordered()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 商品を購入済みにする（ordersテーブルに登録）
        Order::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都千代田区1-1-1',
            'building' => 'テストビル',
            'payment_id' =>  Payment::factory(),
            'price' => 1000,
        ]);

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        // ページに "Sold" が表示されているか確認
        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_user_does_not_see_own_items_in_listing()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        // 自分の商品
        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品'
        ]);

        // 他人の商品
        Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品'
        ]);

        $response = $this->actingAs($user)->get(route('index'));

        $response->assertSee('他人の商品');
        $response->assertDontSee('自分の商品');
    }
}

