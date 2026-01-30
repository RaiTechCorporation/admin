<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('tbl_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Groovsta');
            $table->string('currency')->default('$');
            $table->decimal('coin_value', 10, 4)->default(0.02);
            $table->integer('min_redeem_coins')->default(150);
            $table->integer('min_followers_for_live')->default(1);
            $table->string('admob_banner')->nullable();
            $table->string('admob_int')->nullable();
            $table->string('admob_banner_ios')->nullable();
            $table->string('admob_int_ios')->nullable();
            $table->boolean('admob_android_status')->default(1);
            $table->boolean('admob_ios_status')->default(0);
            $table->integer('max_upload_daily')->default(99);
            $table->integer('max_story_daily')->default(10);
            $table->integer('max_comment_daily')->default(5);
            $table->integer('max_comment_reply_daily')->default(5);
            $table->integer('max_post_pins')->default(3);
            $table->integer('max_comment_pins')->default(1);
            $table->integer('max_images_per_post')->default(5);
            $table->integer('max_user_links')->default(1);
            $table->integer('live_min_viewers')->default(25);
            $table->integer('live_timeout')->default(22);
            $table->boolean('live_battle')->default(1);
            $table->boolean('live_dummy_show')->default(1);
            $table->string('zego_app_id')->nullable();
            $table->string('zego_app_sign')->nullable();
            $table->boolean('is_compress')->default(1);
            $table->boolean('is_deepAR')->default(0);
            $table->string('deepar_android_key')->nullable();
            $table->string('deepar_iOS_key')->nullable();
            $table->boolean('is_withdrawal_on')->default(1);
            $table->string('help_mail')->nullable();
            $table->boolean('is_content_moderation')->default(1);
            $table->string('sight_engine_api_user')->nullable();
            $table->string('sight_engine_api_secret')->nullable();
            $table->string('sight_engine_image_workflow_id')->nullable();
            $table->string('sight_engine_video_workflow_id')->nullable();
            $table->boolean('gif_support')->default(1);
            $table->string('giphy_key')->nullable();
            $table->boolean('watermark_status')->default(1);
            $table->text('watermark_image')->nullable();
            $table->boolean('registration_bonus_status')->default(1);
            $table->integer('registration_bonus_amount')->default(100);
            $table->longText('privacy_policy')->nullable();
            $table->longText('terms_of_uses')->nullable();
            $table->string('place_api_access_token')->nullable();
            $table->string('uri_scheme')->nullable();
            $table->string('play_store_download_link')->nullable();
            $table->string('app_store_download_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_settings');
    }
};
