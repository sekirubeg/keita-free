<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $guarded = [];
    protected static function booted()
    {
        static::preventLazyLoading(false);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
