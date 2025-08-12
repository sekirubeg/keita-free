<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained()->onDelete('cascade'); // 取引ID
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade'); // 評価したユーザーID
            $table->foreignId('evaluated_id')->constrained('users')->onDelete('cascade'); // 評価されたユーザーID
            $table->tinyInteger('rating'); // 評価（例: 1〜5）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
