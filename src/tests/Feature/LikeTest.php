<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_an_item()
    {
        // ユーザーとアイテム作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);

        // いいね前にlikesテーブルにデータがないことを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいねアクションを送信
        $response = $this->post(route('like.toggle', $item->id));

        // リダイレクトを確認（例：商品詳細ページなど）
        $response->assertRedirect();

        // likesテーブルにデータが追加されているか確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品詳細ページにいいね数が反映されているか確認（オプション）
        $response = $this->get(route('item.show', $item->id));
        $response->assertSee((string) Like::where('item_id', $item->id)->count());
    }
}
