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
        Schema::create('package_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Package::class);
            $table->foreignIdFor(\App\Models\Exam::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_exams');
    }
};
