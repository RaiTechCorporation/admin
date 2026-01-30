<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('tbl_sound', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->string('artist');
            $table->text('image')->nullable();
            $table->text('audio_url')->nullable();
            $table->string('added_by')->nullable();
            $table->integer('post_count')->default(0);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('tbl_sound_category')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_sound');
    }
};
