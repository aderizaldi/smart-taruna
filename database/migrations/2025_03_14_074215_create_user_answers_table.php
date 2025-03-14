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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\UserExam::class)->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Question::class)->cascadeOnDelete();
            $table->integer('order_number');
            $table->foreignIdFor(\App\Models\AnswerChoice::class)->nullable();
            $table->boolean('is_marked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
