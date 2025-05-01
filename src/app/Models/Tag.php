<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::preventLazyLoading(false);
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_tag');
    }
}
