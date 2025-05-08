<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(), // ✅ これが必要
            'description' => $this->faker->sentence(), // ← これを追加
            'price' => $this->faker->numberBetween(100, 1000), // もし必須なら
            'image_at' => $this->faker->imageUrl(),
            'user_id' => User::factory(),  // テスト用なので仮でもOK（または User::factory()）
        ];
    }
}
