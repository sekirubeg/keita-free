<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;

class CommentTest extends TestCase
{
    use RefreshDatabase;

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


        $response->assertStatus(302); // ステータスコード確認
        // コメントが保存されているか確認
        
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body' => 'これはテストコメントです。',
        ]);
        
    }
    // public function test_guest_user_cannot_post_comment()
    // {
    //     $item = Item::factory()->create();

    //     $response = $this->post('/comments', [
    //         'item_id' => $item->id,
    //         'body' => '未ログインでのコメント',
    //     ]);

    //     // 未ログインなのでリダイレクトされる（通常は /login）
    //     $response->assertRedirect('/login');

    //     // DBにコメントが保存されていないことを確認
    //     $this->assertDatabaseMissing('comments', [
    //         'body' => '未ログインでのコメント',
    //     ]);
    // }
    public function test_comment_validation_error_when_body_is_empty()
    {
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/comments', [
            'item_id' => $item->id,
            'body' => '', // 空のコメント
        ]);

        $response->assertSessionHasErrors(['body']); // バリデーションエラーが発生しているか
    }
    public function test_comment_validation_error_when_body_is_too_long()
    {
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create();

        $this->actingAs($user);

        $longComment = str_repeat('あ', 256); // 256文字のコメント

        $response = $this->post('/comments', [
            'item_id' => $item->id,
            'body' => $longComment,
        ]);

        $response->assertSessionHasErrors(['body']); // バリデーションエラーを確認
    }
}
