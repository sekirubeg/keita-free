<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationFactory> */
    use HasFactory;

    protected $guarded = [];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_id');
    }
}
