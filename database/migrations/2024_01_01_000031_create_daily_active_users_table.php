<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('daily_active_users', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('user_count')->default(0);
            $table->timestamps();
            $table->unique('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_active_users');
    }
};
