<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('tbl_post', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sound_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('post_type')->nullable();
            $table->text('thumbnail')->nullable();
            $table->longText('video')->nullable();
            $table->string('hashtags')->nullable();
            $table->longText('mentioned_user_ids')->nullable();
            $table->string('place_title')->nullable();
            $table->decimal('place_lat', 10, 8)->nullable();
            $table->decimal('place_lon', 11, 8)->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('saves')->default(0);
            $table->integer('shares')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->longText('metadata')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->foreign('sound_id')->references('id')->on('tbl_sound')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_post');
    }
};
