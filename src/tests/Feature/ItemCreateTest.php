<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;
    //商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格）
    public function test_item_can_be_created_with_valid_data()
    {
        
        $user = User::factory()->create([
            'name'      => 'テスト太郎',
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区1-2-3',
            'building' => 'テストビル101',
        ]);

        $tag = Tag::factory()->create(['name' => 'カテゴリ名']);

        $postData = [
            'name'        => 'テスト商品',
            'description' => '商品の説明文',
            'price'       => 5000,
            'status'      => 1,
            'brand'       => 'テストブランド',
            'image_at'    =>  UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'tags'     => [$tag->id],
        ];

        $response = $this->actingAs($user)->post(route('item.store'), $postData);
        $response->assertRedirect(); // 成功後リダイレクトされる前提

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('items', [
            'name'        => 'テスト商品',
            'description' => '商品の説明文',
            'price'       => 5000,
            'status'      => '1',
            'brand'       => 'テストブランド',
            'user_id'     => $user->id,
        ]);

        $item = Item::where('name', 'テスト商品')->first();
        $this->assertTrue($item->tags->contains('id', $tag->id));
    }
}
