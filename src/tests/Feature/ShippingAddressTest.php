<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;
    //送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_shipping_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create([
            'post_code' => '000-0000',
            'address'   => '旧住所',
            'building'  => '旧ビル',
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('address.store', ['id' => $item->id]), [
            'name'      => 'テスト太郎',
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区1-2-3',
            'building'  => '新ビル101',
        ])->assertRedirect(route('item.purchase', $item->id));

        $response = $this->get(route('item.purchase', $item->id));

        // セッションの住所が正しく表示されているか確認
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('新ビル101');
    }

    public function test_shipping_address_is_saved_with_order()
    {
        $user = User::factory()->create([
            'post_code' => '000-0000',
            'address'   => '旧住所',
            'building'  => '旧ビル',
        ]);
        Payment::factory()->create([
            'id' => 1,
            'payment_method' => 'クレジットカード',
        ]);
        

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('address.store', ['id' => $item->id]), [
            'name'      => 'テスト太郎',
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区1-2-3',
            'building'  => '新ビル101',
        ])->assertRedirect(route('item.purchase', $item->id));
        // 送付先住所をセッションに保存（住所変更）


        // 商品を購入する（購入処理）
        $this->actingAs(
            $user)->post(route('item.checkout', ['id' => $item->id]), [
            'paymentId' => 1,
        ]);

        Order::create([
            'user_id'   => $user->id,
            'item_id'   => $item->id,
            'post_code' => session('purchase_post_code'),
            'address'   => session('purchase_address'),
            'building'  => session('purchase_building'),
            'payment_id' => 1,
            'price' => $item->price,
        ]);
        // orders テーブルに住所情報が正しく保存されているか確認
        $this->assertDatabaseHas('orders', [
            'user_id'   => $user->id,
            'item_id'   => $item->id,
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区1-2-3',
            'building'  => '新ビル101',
        ]);
    }
}
