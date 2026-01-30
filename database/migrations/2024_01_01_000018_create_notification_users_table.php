<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('notification_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->longText('message');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('tbl_users')->onDelete('set null');
            $table->foreign('post_id')->references('id')->on('tbl_post')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_users');
    }
};
