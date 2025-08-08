<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;;

use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $faker = FakerFactory::create('ja_JP');

        $items = [
            [
                'name' => '腕時計',
                'description' => "スタイリッシュなデザインのメンズ腕時計",
                'price' => 15000,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'status' => 1,
            ],
            [
                'name' => 'HDD',
                'description' => "高速で信頼性の高いハードディスク",
                'price' => 5000,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'status' => 2,
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => "新鮮な玉ねぎ3束セット",
                'price' => 300,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'status' => 3,
            ],
            [
                'name' => '革靴',
                'description' => "クラシックなデザインの革靴",
                'price' => 4000,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'status' => 4,
            ],
            [
                'name' => 'ノートPC',
                'description' => "高性能なノートパソコン",
                'price' => 45000,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'status' => 1,
            ],
            [
                'name' => 'マイク',
                'description' => "高音質なレコーディング用マイク",
                'price' => 8000,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'status' => 2,
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => "おしゃれなショルダーバッグ",
                'price' => 3500,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'status' => 3,
            ],
            [
                'name' => 'タンブラー',
                'description' => "使いやすいタンブラー",
                'price' => 500,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'status' => 4,
            ],
            [
                'name' => 'コーヒーミル',
                'description' => "手動のコーヒーミル",
                'price' => 4000,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'status' => 1,
            ],
            [
                'name' => 'メイクセット',
                'description' => "便利なメイクアップセット",
                'price' => 2500,
                'image_at' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'status' => 2,
            ],
        ];

        foreach ($items as $item) {
            Item::factory()->create($item);
        }


        $payments =
            [
                "クレジットカード",
                "コンビニ決済",
                "PayPay"
            ];

        foreach ($payments as $payment) {
            Payment::factory()->create([
                'payment_method' => $payment,
            ]);
        }


        $tags =
            [
                "ファッション",
                "家電",
                "インテリア",
                "レディース",
                "メンズ",
                "コスメ",
                "本",
                "ゲーム",
                "スポーツ",
                "キッチン",
                "ハンドメイド",
                "アクセサリー",
                "おもちゃ",
                "ベビー・キッズ",
            ];
        foreach ($tags as $tag) {
            Tag::factory()->create([
                'name' => $tag,
            ]);
        }
    }
}
