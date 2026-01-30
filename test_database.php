<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "====================================\n";
echo "Database Diagnostic Report\n";
echo "====================================\n\n";

try {
    echo "[1] Testing Database Connection...\n";
    DB::connection()->getPdo();
    echo "✓ Database connected successfully\n\n";
    
    echo "[2] Database Configuration\n";
    $config = config('database.connections.mysql');
    echo "  Host: " . $config['host'] . "\n";
    echo "  Database: " . $config['database'] . "\n";
    echo "  User: " . $config['username'] . "\n\n";
    
    echo "[3] Testing Read Operations...\n";
    try {
        $tableCount = DB::select("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?", [config('database.connections.mysql.database')]);
        echo "✓ Read OK - Found " . $tableCount[0]->count . " tables\n";
    } catch (\Exception $e) {
        echo "✗ Read Failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n[4] Testing Write Operations...\n";
    try {
        // Try to insert a test record
        $testId = DB::table('user_auth_tokens')->insertGetId([
            'user_id' => 1,
            'auth_token' => 'test_' . time(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "✓ Write OK - Inserted test record with ID: " . $testId . "\n";
        
        // Clean up
        DB::table('user_auth_tokens')->where('id', $testId)->delete();
        echo "✓ Cleanup OK - Deleted test record\n";
    } catch (\Exception $e) {
        echo "✗ Write Failed: " . $e->getMessage() . "\n";
        echo "  Error Code: " . $e->getCode() . "\n";
        echo "  This indicates database write permissions issue\n";
    }
    
    echo "\n[5] Table Structure Check...\n";
    $tables = DB::select("SHOW TABLES");
    $criticalTables = [
        'users',
        'user_auth_tokens',
        'posts',
        'followers',
        'settings'
    ];
    
    foreach ($criticalTables as $table) {
        try {
            DB::table($table)->limit(1)->get();
            echo "✓ " . $table . " - OK\n";
        } catch (\Exception $e) {
            echo "✗ " . $table . " - Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n[6] Checking Table Privileges...\n";
    $userId = config('database.connections.mysql.username');
    $host = config('database.connections.mysql.host');
    echo "  Current User: " . $userId . "@" . $host . "\n";
    echo "  Note: To check privileges, you may need to run:\n";
    echo "  SHOW GRANTS FOR '" . $userId . "'@'%';\n\n";
    
    echo "[7] Testing User Auth Token Table...\n";
    try {
        $count = DB::table('user_auth_tokens')->count();
        echo "✓ user_auth_tokens - Read: " . $count . " records\n";
        
        // Check table structure
        $columns = DB::select("DESCRIBE user_auth_tokens");
        echo "✓ Table columns:\n";
        foreach ($columns as $col) {
            echo "    - " . $col->Field . " (" . $col->Type . ")\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Fatal Error: " . $e->getMessage() . "\n";
    echo "  Stack: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n====================================\n";
echo "Diagnostic Complete\n";
echo "====================================\n";
