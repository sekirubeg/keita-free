<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_an_item()
    {
        $user = User::factory()->create([
            'image_at' => 'dummy.jpg', // または null チェック対応
        ]);

        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);
        $this->assertEquals(0, $item->likes()->count());

        $response = $this->actingAs($user)->get(route('item.show', $item->id));
        $response->assertSee($item->likes()->count());
        // いいねアクションを実行
        $response = $this->actingAs($user)->post("/likes/{$item->id}");

        // 正常にリダイレクトされる
        $response->assertStatus(302);

        // データベースにいいねが登録されている
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $item->refresh()->loadCount('likes');
        $this->assertEquals(1, $item->likes()->count());
        $response = $this->actingAs($user)->get(route('item.show', $item->id));
        $response->assertSee($item->likes()->count());
    }
}
