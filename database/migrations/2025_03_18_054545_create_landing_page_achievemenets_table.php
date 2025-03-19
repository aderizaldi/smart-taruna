<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('landing_page_achievemenets', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('amount');
            $table->string('icon');
            $table->timestamps();
        });

        DB::table('landing_page_achievemenets')->insert([
            [
                'description' => 'Try Out Tersedia',
                'amount' => '190+',
                'icon' => 'users',
            ],
            [
                'description' => 'Teachers',
                'amount' => '50',
                'icon' => 'user-tie',
            ],
            [
                'description' => 'Courses',
                'amount' => '100',
                'icon' => 'book-open',
            ],
            [
                'description' => 'Graduates',
                'amount' => '500',
                'icon' => 'user-graduate',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_achievemenets');
    }
};
