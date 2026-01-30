<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('tbl_coin_plan', function (Blueprint $table) {
            $table->id();
            $table->integer('coin_amount');
            $table->decimal('coin_plan_price', 15, 2);
            $table->string('playstore_product_id')->nullable();
            $table->string('appstore_product_id')->nullable();
            $table->text('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_coin_plan');
    }
};
