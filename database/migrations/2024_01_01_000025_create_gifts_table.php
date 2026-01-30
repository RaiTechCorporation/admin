<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('gift_name');
            $table->text('gift_image')->nullable();
            $table->integer('gift_cost');
            $table->longText('gift_description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
