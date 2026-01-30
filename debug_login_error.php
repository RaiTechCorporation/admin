<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use App\Models\Users;
use App\Models\GlobalFunction;

echo "Testing prepareUserFullData() method...\n";
echo "=======================================\n\n";

try {
    // Test with user ID 1 (should exist from seeding)
    $userId = 1;
    echo "Attempting to load user data for ID: " . $userId . "\n";
    
    $user = GlobalFunction::prepareUserFullData($userId);
    
    if ($user) {
        echo "✓ Successfully loaded user data:\n";
        echo json_encode($user, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "✗ User not found with ID: " . $userId . "\n";
        echo "Creating a test user first...\n";
        
        $testUser = new Users();
        $testUser->identity = 'debug_test_' . time();
        $testUser->fullname = 'Debug Test User';
        $testUser->username = 'debugtest' . rand(1000, 9999);
        $testUser->device_token = 'test_token';
        $testUser->device = 'web';
        $testUser->login_method = 'test';
        $testUser->save();
        
        echo "Created test user with ID: " . $testUser->id . "\n";
        
        echo "\nAttempting to load new user data...\n";
        $newUser = GlobalFunction::prepareUserFullData($testUser->id);
        
        if ($newUser) {
            echo "✓ Successfully loaded new user data:\n";
            echo json_encode($newUser, JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "✗ Failed to load new user data\n";
        }
    }
} catch (\Exception $e) {
    echo "✗ Exception thrown:\n";
    echo "Error Type: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
}
