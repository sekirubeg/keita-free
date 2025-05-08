<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;


class MylistTest extends TestCase
{
    use RefreshDatabase;

    //いいねした商品だけが表示される
    public function test_only_liked_items_are_displayed_on_mylist_page()
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create();
        $nonLikedItem = Item::factory()->create();

        $user->likes()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?page=mylist');


        $response->assertStatus(200);
        $response->assertSee($likedItem->name);
        $response->assertDontSee($nonLikedItem->name);
    }

    //自分が出品した商品は表示されない
    public function test_user_does_not_see_own_items_in_mylist()
    {
        // 出品者（ログインユーザー）
        $user = User::factory()->create();

        // 他のユーザーとその出品
        $otherUser = User::factory()->create();
        $otherItem = Item::factory()->create(['user_id' => $otherUser->id]);

        // ログインユーザーの出品
        $ownItem = Item::factory()->create(['user_id' => $user->id]);

        // ログインユーザーが両方にいいね
        $user->likes()->attach([$ownItem->id, $otherItem->id]);

        // マイリスト表示
        $response = $this->actingAs($user)->get('/?page=mylist');

        // 自分の商品は表示されないこと
        $response->assertStatus(200);
        $response->assertDontSee($ownItem->name);
        // 他人の商品は表示されること
        $response->assertSee($otherItem->name);
    }
    



    //購入済み商品は「Sold」と表示される
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

    //未認証の場合は何も表示されない(login画面へリダイレクト)
    public function test_guest_is_redirected_from_mylist_page()
    {
        $response = $this->get('/?page=mylist');

        $response->assertRedirect('/login'); // Laravelのデフォルトログインルート
    }
}

