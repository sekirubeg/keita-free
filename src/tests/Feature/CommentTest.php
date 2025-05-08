<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_logged_in_user_can_post_comment()
    {
        // ユーザーと商品を作成
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create();

        // ログイン状態にする
        $this->actingAs($user);

        // コメント送信
        $response = $this->post("/comments", [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'これはテストコメントです。',
        ]);

        // コメントが保存されているか確認
        
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'これはテストコメントです。',
        ]);

    }
}
