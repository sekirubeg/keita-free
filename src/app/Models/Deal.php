<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * この取引が関連する商品を取得する
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * この取引の購入者を取得する
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * この取引の販売者を取得する
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * この取引のメッセージを取得する
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
    /**
     * この取引の相手（購入者または販売者）を取得する
     * 取引の販売者がログイン中のユーザーなら、購入者を返す
     * それ以外の場合は販売者を返す
     */
    public function partner()
    {
        // もし、この取引の販売者がログイン中のユーザーなら
        if ($this->seller_id === auth()->id()) {
            // 取引相手は購入者(buyer)
            return $this->buyer;
        }

        // それ以外の場合（自分が購入者なら）
        // 取引相手は販売者(seller)
        return $this->seller;
    }
}
