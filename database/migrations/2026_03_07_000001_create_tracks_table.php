<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('genre');
            $table->string('subgenre')->nullable();
            $table->string('mood')->nullable();
            $table->string('cover_art_path');
            $table->string('extracted_color')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('preview_path')->nullable();
            $table->boolean('is_free')->default(false);
            $table->string('patreon_url')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
