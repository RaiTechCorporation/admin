<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?", [config('database.connections.mysql.database')]);

echo "Existing tables in database:\n";
echo "============================\n";
foreach ($tables as $table) {
    echo "- " . $table->TABLE_NAME . "\n";
}
echo "\n";

$requiredTables = [
    'tbl_admin',
    'tbl_users',
    'tbl_settings',
    'tbl_sound_category',
    'tbl_sound',
    'tbl_post',
    'tbl_hash_tags',
    'tbl_followers',
    'tbl_user_blocks',
    'post_likes',
    'tbl_comments',
    'comment_replies',
    'comment_likes',
    'stories',
    'post_saves',
    'post_images',
    'user_links',
    'notification_users',
    'notification_admin',
    'report_posts',
    'report_users',
    'report_reasons',
    'user_levels',
    'tbl_coin_plan',
    'gifts',
    'tbl_redeem_gateways',
    'tbl_redeem_request',
    'onboarding_screens',
    'deepar_filters',
    'dummy_live_videos',
    'daily_active_users',
    'languages',
    'username_restrictions',
];

$existingTableNames = array_map(function($t) { return $t->TABLE_NAME; }, $tables);

echo "Missing tables:\n";
echo "================\n";
$missing = 0;
foreach ($requiredTables as $table) {
    if (!in_array($table, $existingTableNames)) {
        echo "- " . $table . "\n";
        $missing++;
    }
}

echo "\nTotal missing: " . $missing . " / " . count($requiredTables) . "\n";
