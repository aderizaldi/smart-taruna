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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Type::class);
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('scoring_type', ['boolean', 'point'])->default('boolean');
            $table->integer('wrong_answer_point')->nullable(); //if scoring type is boolean
            $table->integer('right_answer_point')->nullable(); //if scoring type is boolean
            $table->integer('passing_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
