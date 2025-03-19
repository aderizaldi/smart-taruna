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
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->longText('quote');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('instagram');
            $table->string('youtube');

            $table->timestamps();
        });

        DB::table('landing_pages')->insert([
            'quote' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Faucibus in libero risus semper habitant arcu eget. Et integer facilisi eget.',
            'email' => 'smarttaruna@example.com',
            'phone' => '08123456789',
            'address' => 'Jl.R.E Martadinata No.158',
            'facebook' => 'smarttaruna',
            'twitter' => 'smarttaruna',
            'instagram' => 'smarttarunaeducation',
            'youtube' => 'smarttaruna',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};
