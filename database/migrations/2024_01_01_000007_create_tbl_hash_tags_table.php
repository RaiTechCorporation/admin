<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('tbl_hash_tags', function (Blueprint $table) {
            $table->id();
            $table->string('hashtag')->unique();
            $table->integer('post_count')->default(0);
            $table->boolean('on_explore')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_hash_tags');
    }
};
