<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('user_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level_name');
            $table->integer('min_points')->default(0);
            $table->integer('max_points')->default(0);
            $table->text('badge_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_levels');
    }
};
