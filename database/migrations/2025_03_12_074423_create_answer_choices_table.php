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
        Schema::create('answer_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Question::class)->constrained()->cascadeOnDelete();
            $table->text('choice_text')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_correct')->nullable(); //if section type is right_or_wrong
            $table->integer('point')->nullable(); //if section type is point
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_choices');
    }
};
