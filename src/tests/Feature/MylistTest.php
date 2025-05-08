<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MylistTest extends TestCase
{

    public function test_only_liked_items_are_displayed_in_mylist()
    {
        $user = \App\Models\User::factory()->create();
        $likedItem = \App\Models\Item::factory()->create();
        $unlikedItem = \App\Models\Item::factory()->create();

        // ユーザーが「いいね」する
        $user->likes()->attach($likedItem->id);

        // ログイン状態にする
        $this->actingAs($user);

        // マイリストページにアクセス（例：/mylist）
        $response = $this->get('/mylist');

        // 「いいね」した商品は表示される
        $response->assertSee($likedItem->name);

    }

    
    public function test_sold_label_is_displayed_on_purchased_items_in_mylist()
    {
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create(['name' => '購入商品']);

        // 商品をいいねする
        $user->likes()->attach($item->id);

        // 購入済みにする（ordersレコードを作成）
        $payment = \App\Models\Payment::firstOrCreate(
            ['payment_method' => 'クレジットカード']
        );

        \App\Models\Order::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都千代田区1-1-1',
            'building' => 'テストビル',
            'payment_id' => $payment->id,
            'price' => $item->price,
        ]);

        $this->actingAs($user);
        $response = $this->get('/mylist');

        $response->assertSee('購入商品');
        $response->assertSee('Sold');
    }
}

