<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('deepar_filters', function (Blueprint $table) {
            $table->id();
            $table->string('filter_name');
            $table->text('filter_image')->nullable();
            $table->string('filter_id')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deepar_filters');
    }
};
