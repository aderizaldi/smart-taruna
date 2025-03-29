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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Exam::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Section::class)->constrained()->cascadeOnDelete();
            $table->text('question_text')->nullable();
            $table->string('image')->nullable();
            $table->text('explanation_text')->nullable();
            $table->string('explanation_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
