<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('stories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('image')->nullable();
            $table->longText('video')->nullable();
            $table->text('thumbnail')->nullable();
            $table->longText('description')->nullable();
            $table->integer('views')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
