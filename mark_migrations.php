<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$migrations = [
    '2024_01_01_000001_create_tbl_admin_table',
    '2024_01_01_000002_create_tbl_users_table',
    '2024_01_01_000003_create_tbl_settings_table',
    '2024_01_01_000004_create_tbl_sound_category_table',
    '2024_01_01_000005_create_tbl_sound_table',
    '2024_01_01_000006_create_tbl_post_table',
    '2024_01_01_000007_create_tbl_hash_tags_table',
    '2024_01_01_000008_create_tbl_followers_table',
    '2024_01_01_000009_create_tbl_user_blocks_table',
    '2024_01_01_000010_create_post_likes_table',
    '2024_01_01_000011_create_tbl_comments_table',
    '2024_01_01_000012_create_comment_replies_table',
    '2024_01_01_000013_create_comment_likes_table',
    '2024_01_01_000014_create_stories_table',
    '2024_01_01_000015_create_post_saves_table',
    '2024_01_01_000016_create_post_images_table',
    '2024_01_01_000017_create_user_links_table',
    '2024_01_01_000018_create_notification_users_table',
    '2024_01_01_000019_create_notification_admin_table',
    '2024_01_01_000020_create_report_posts_table',
    '2024_01_01_000021_create_report_users_table',
    '2024_01_01_000022_create_report_reasons_table',
    '2024_01_01_000023_create_user_levels_table',
    '2024_01_01_000024_create_tbl_coin_plan_table',
    '2024_01_01_000025_create_gifts_table',
    '2024_01_01_000026_create_tbl_redeem_gateways_table',
    '2024_01_01_000027_create_tbl_redeem_request_table',
    '2024_01_01_000028_create_onboarding_screens_table',
    '2024_01_01_000029_create_deepar_filters_table',
    '2024_01_01_000030_create_dummy_live_videos_table',
    '2024_01_01_000031_create_daily_active_users_table',
    '2024_01_01_000032_create_languages_table',
    '2024_01_01_000033_create_username_restrictions_table',
];

echo "Marking migrations as completed...\n";
$batch = DB::table('migrations')->max('batch') + 1;

foreach ($migrations as $migration) {
    // Check if migration already exists
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch
        ]);
        echo "✓ Marked: " . $migration . "\n";
    } else {
        echo "- Already marked: " . $migration . "\n";
    }
}

echo "\nAll migrations marked as completed.\n";
