<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\User;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_items_can_be_searched_by_partial_name()
    {
        // テスト用のデータを作成
        Item::factory()->create(['name' => 'Apple Watch']);
        Item::factory()->create(['name' => 'Galaxy Watch']);
        Item::factory()->create(['name' => 'AirPods']);

        // "Watch" で検索
        $response = $this->get('/?search=Watch');

        // "Watch" を含む商品が表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('Apple Watch');
        $response->assertSee('Galaxy Watch');
        $response->assertDontSee('AirPods');
    }
    public function test_search_keyword_is_retained_on_mylist_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'テスト商品']);
        $item2 = Item::factory()->create(['name' => 'ダミー']);

        // いいね登録
        $user->likes()->attach($item->id);
        $user->likes()->attach($item2->id);

        // ホームページで検索クエリを送信し、セッションに保存されることを前提とする
        $this->actingAs($user)->get('/?search=テスト商品');

        // マイリストに遷移（検索ワードがセッションから使われる想定）
        $response = $this->actingAs($user)->get('/?page=mylist');

        // 検索ワードに一致するアイテムが表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertDontSee('ダミー');
    }
}
