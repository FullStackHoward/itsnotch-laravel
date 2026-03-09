<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->text('genre')->change();
            $table->text('subgenre')->nullable()->change();
            $table->text('mood')->nullable()->change();
            $table->text('type')->nullable()->after('mood');
        });
    }

    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('genre')->change();
            $table->string('subgenre')->nullable()->change();
            $table->string('mood')->nullable()->change();
        });
    }
};
