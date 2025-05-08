<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Like;
use App\Models\tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;
    //必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）

    public function test_item_detail_page_displays_all_required_information()
    {
        // ユーザーとアイテム作成
        $user = User::factory()->create(['name' => 'テストユーザー']);
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1234,
            'status' => '1',

            'description' => '商品の説明です。',
            'image_at' => 'test.jpg',
            'user_id' => $user->id,
        ]);
        $tag = Tag::firstOrCreate(['name' => 'カテゴリ名']);


        $item->tags()->attach($tag->id);

        // いいねとコメントの追加
        Like::factory()->create(['item_id' => $item->id, 'user_id' => $user->id]);
        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'body' => 'コメント内容です。',
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get(route('item.show', $item->id));

        // 表示確認
        $response->assertStatus(200);
        $response->assertSee('テスト商品');             // 商品名
        $response->assertSee('テストブランド');         // ブランド名
        $response->assertSee('¥1,234');                   // 価格
        $response->assertSee('カテゴリ名');             // カテゴリ
        $response->assertSee('良好');                   // 商品の状態
        $response->assertSee('商品の説明です。');       // 説明文
        $response->assertSee('1');                      // いいね数、コメント数（両方1）
        $response->assertSee('テストユーザー');         // コメントしたユーザー
        $response->assertSee('コメント内容です。');     // コメント内容
        $response->assertSee('test.jpg');               // 画像パス（表示確認）
    }
    //複数選択されたカテゴリが表示されているか
    public function test_item_detail_page_displays_multiple_tags()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1234,
            'status' => '1',
            'description' => '商品の説明です。',
            'image_at' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $tag1 = Tag::firstOrCreate(['name' => 'カテゴリA']);
        $tag2 = Tag::firstOrCreate(['name' => 'カテゴリB']);
        $item->tags()->attach([$tag1->id, $tag2->id]);

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200);
        $response->assertSee('カテゴリA');
        $response->assertSee('カテゴリB');
    }
}