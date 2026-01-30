<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Creating missing tables...\n";
echo "============================\n\n";

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Missing tables to create
$missingTables = [
    'tbl_user_blocks' => function() {
        Schema::create('tbl_user_blocks', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('to_user_id');
            $table->timestamps();
            $table->unique(['user_id', 'to_user_id']);
        });
    },
    'post_likes' => function() {
        Schema::create('post_likes', function ($table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->unique(['post_id', 'user_id']);
        });
    },
    'report_users' => function() {
        Schema::create('report_users', function ($table) {
            $table->id();
            $table->unsignedBigInteger('reported_user_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reason_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    },
];

foreach ($missingTables as $tableName => $createCallback) {
    if (!Schema::hasTable($tableName)) {
        try {
            $createCallback();
            echo "✓ Created: " . $tableName . "\n";
        } catch (\Exception $e) {
            echo "✗ Error creating " . $tableName . ": " . $e->getMessage() . "\n";
        }
    } else {
        echo "- Already exists: " . $tableName . "\n";
    }
}

// Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n============================\n";
echo "Missing tables creation complete.\n";
