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

// Missing tables to create
$missingTables = [
    'tbl_user_blocks' => function() {
        Schema::create('tbl_user_blocks', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('to_user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->foreign('to_user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->unique(['user_id', 'to_user_id']);
        });
    },
    'post_likes' => function() {
        Schema::create('post_likes', function ($table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('tbl_post')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
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
            $table->foreign('reported_user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
        });
    },
    'gifts' => function() {
        Schema::create('gifts', function ($table) {
            $table->id();
            $table->string('gift_name');
            $table->text('gift_image')->nullable();
            $table->integer('gift_cost');
            $table->longText('gift_description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    },
    'username_restrictions' => function() {
        Schema::create('username_restrictions', function ($table) {
            $table->id();
            $table->string('restricted_username')->unique();
            $table->longText('reason')->nullable();
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

echo "\n============================\n";
echo "Missing tables creation complete.\n";
