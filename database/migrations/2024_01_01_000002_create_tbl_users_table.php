<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('tbl_users', function (Blueprint $table) {
            $table->id();
            $table->string('identity')->unique();
            $table->string('fullname');
            $table->string('username')->unique();
            $table->string('password')->nullable();
            $table->text('profile_photo')->nullable();
            $table->longText('bio')->nullable();
            $table->string('user_email')->nullable();
            $table->string('mobile_country_code')->nullable();
            $table->string('user_mobile_no')->nullable();
            $table->string('device_token')->nullable();
            $table->string('device')->nullable();
            $table->string('login_method')->nullable();
            $table->boolean('is_verify')->default(false);
            $table->boolean('is_dummy')->default(false);
            $table->boolean('is_freez')->default(false);
            $table->boolean('is_moderator')->default(false);
            $table->decimal('coin_wallet', 15, 2)->default(0);
            $table->decimal('coin_collected_lifetime', 15, 2)->default(0);
            $table->decimal('coin_gifted_lifetime', 15, 2)->default(0);
            $table->decimal('coin_purchased_lifetime', 15, 2)->default(0);
            $table->integer('following_count')->default(0);
            $table->integer('follower_count')->default(0);
            $table->integer('total_post_likes_count')->default(0);
            $table->string('country')->nullable();
            $table->string('countryCode')->nullable();
            $table->string('region')->nullable();
            $table->string('regionName')->nullable();
            $table->string('city')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lon', 11, 8)->nullable();
            $table->boolean('notify_post_like')->default(true);
            $table->boolean('notify_post_comment')->default(true);
            $table->boolean('notify_follow')->default(true);
            $table->boolean('notify_mention')->default(true);
            $table->boolean('notify_gift_received')->default(true);
            $table->boolean('notify_chat')->default(true);
            $table->timestamp('app_last_used_at')->nullable();
            $table->string('who_can_view_post')->default('everyone');
            $table->boolean('show_my_following')->default(true);
            $table->boolean('receive_message')->default(true);
            $table->longText('saved_music_ids')->nullable();
            $table->string('app_language')->default('en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};
