<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $guarded = [];
    protected static function booted()
    {
        static::preventLazyLoading(false);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
