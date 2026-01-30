<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Admin;
use App\Models\GlobalSettings;

echo "Seeding initial data...\n";
echo "============================\n\n";

// Check and create admin user
echo "[1] Creating Admin User...\n";
$adminExists = DB::table('tbl_admin')->where('admin_username', 'admin')->exists();

if (!$adminExists) {
    try {
        $defaultPassword = 'admin123';
        $encryptedPassword = Crypt::encrypt($defaultPassword);
        
        DB::table('tbl_admin')->insert([
            'admin_username' => 'admin',
            'admin_password' => $encryptedPassword,
            'user_type' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✓ Admin user created\n";
        echo "  Username: admin\n";
        echo "  Password: admin123\n";
    } catch (\Exception $e) {
        echo "✗ Error creating admin user: " . $e->getMessage() . "\n";
    }
} else {
    echo "- Admin user already exists\n";
}

// Check and create default settings
echo "\n[2] Creating Default Settings...\n";
$settingsExists = DB::table('tbl_settings')->exists();

if (!$settingsExists) {
    try {
        DB::table('tbl_settings')->insert([
            'app_name' => 'Groovsta',
            'currency' => '$',
            'coin_value' => 0.02,
            'min_redeem_coins' => 150,
            'min_followers_for_live' => 1,
            'admob_android_status' => 1,
            'admob_ios_status' => 0,
            'max_upload_daily' => 99,
            'max_story_daily' => 10,
            'max_comment_daily' => 5,
            'max_comment_reply_daily' => 5,
            'max_post_pins' => 3,
            'max_comment_pins' => 1,
            'max_images_per_post' => 5,
            'max_user_links' => 1,
            'live_min_viewers' => 25,
            'live_timeout' => 22,
            'live_battle' => 1,
            'live_dummy_show' => 1,
            'is_compress' => 1,
            'is_deepAR' => 0,
            'is_withdrawal_on' => 1,
            'is_content_moderation' => 1,
            'gif_support' => 1,
            'watermark_status' => 1,
            'registration_bonus_status' => 1,
            'registration_bonus_amount' => 100,
            'help_mail' => 'support@Groovsta.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✓ Default settings created\n";
    } catch (\Exception $e) {
        echo "✗ Error creating settings: " . $e->getMessage() . "\n";
    }
} else {
    echo "- Settings already exist\n";
}

// Create report reasons
echo "\n[3] Creating Report Reasons...\n";
$reasons = [
    'Inappropriate content',
    'Harassment or bullying',
    'Spam',
    'Copyright infringement',
    'Misleading information',
    'Violence or dangerous content',
    'Sexual content',
    'Hate speech',
    'Scam or fraud',
    'Other'
];

try {
    $reasonsCount = DB::table('report_reasons')->count();
    
    if ($reasonsCount == 0) {
        foreach ($reasons as $reason) {
            DB::table('report_reasons')->insert([
                'reason' => $reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        echo "✓ Report reasons created (" . count($reasons) . " reasons)\n";
    } else {
        echo "- Report reasons already exist (" . $reasonsCount . " reasons)\n";
    }
} catch (\Exception $e) {
    echo "✗ Error creating report reasons: " . $e->getMessage() . "\n";
}

// Create default language
echo "\n[4] Creating Default Language...\n";
try {
    $langExists = DB::table('languages')->where('code', 'en')->exists();
    
    if (!$langExists) {
        DB::table('languages')->insert([
            'code' => 'en',
            'title' => 'English',
            'localized_title' => 'English',
            'status' => 1,
            'is_default' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✓ Default language created (English)\n";
    } else {
        echo "- Default language already exists\n";
    }
} catch (\Exception $e) {
    echo "✗ Error creating language: " . $e->getMessage() . "\n";
}

// Create sample coin plans
echo "\n[5] Creating Sample Coin Plans...\n";
$coinPlans = [
    ['coin_amount' => 100, 'coin_plan_price' => 0.99, 'playstore_product_id' => 'com.Groovsta.coins100', 'appstore_product_id' => 'Groovsta.coins.100'],
    ['coin_amount' => 500, 'coin_plan_price' => 4.99, 'playstore_product_id' => 'com.Groovsta.coins500', 'appstore_product_id' => 'Groovsta.coins.500'],
    ['coin_amount' => 1000, 'coin_plan_price' => 9.99, 'playstore_product_id' => 'com.Groovsta.coins1000', 'appstore_product_id' => 'Groovsta.coins.1000'],
    ['coin_amount' => 5000, 'coin_plan_price' => 49.99, 'playstore_product_id' => 'com.Groovsta.coins5000', 'appstore_product_id' => 'Groovsta.coins.5000'],
];

try {
    $planCount = DB::table('tbl_coin_plan')->count();
    
    if ($planCount == 0) {
        foreach ($coinPlans as $plan) {
            DB::table('tbl_coin_plan')->insert([
                'coin_amount' => $plan['coin_amount'],
                'coin_plan_price' => $plan['coin_plan_price'],
                'playstore_product_id' => $plan['playstore_product_id'],
                'appstore_product_id' => $plan['appstore_product_id'],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        echo "✓ Coin plans created (" . count($coinPlans) . " plans)\n";
    } else {
        echo "- Coin plans already exist (" . $planCount . " plans)\n";
    }
} catch (\Exception $e) {
    echo "✗ Error creating coin plans: " . $e->getMessage() . "\n";
}

// Create redeem gateways
echo "\n[6] Creating Redeem Gateways...\n";
$gateways = [
    ['title' => 'PayPal', 'gateway' => 'paypal'],
    ['title' => 'Stripe', 'gateway' => 'stripe'],
    ['title' => 'Bank Transfer', 'gateway' => 'bank_transfer'],
];

try {
    $gatewayCount = DB::table('tbl_redeem_gateways')->count();
    
    if ($gatewayCount == 0) {
        foreach ($gateways as $gateway) {
            DB::table('tbl_redeem_gateways')->insert([
                'title' => $gateway['title'],
                'gateway' => $gateway['gateway'],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        echo "✓ Redeem gateways created (" . count($gateways) . " gateways)\n";
    } else {
        echo "- Redeem gateways already exist (" . $gatewayCount . " gateways)\n";
    }
} catch (\Exception $e) {
    echo "✗ Error creating redeem gateways: " . $e->getMessage() . "\n";
}

echo "\n============================\n";
echo "Initial data seeding complete!\n";
echo "============================\n";
