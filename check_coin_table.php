<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select("DESCRIBE tbl_coin_plan");

echo "Columns in tbl_coin_plan:\n";
foreach ($columns as $col) {
    echo "- " . $col->Field . " (" . $col->Type . ") " . ($col->Null == 'NO' ? 'NOT NULL' : 'NULL') . "\n";
}

// Try to get existing records
echo "\nExisting records:\n";
$records = DB::table('tbl_coin_plan')->get();
foreach ($records as $record) {
    echo "- " . json_encode($record) . "\n";
}
