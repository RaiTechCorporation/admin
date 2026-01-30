<?php

$migrationDir = __DIR__ . '/database/migrations/';
$files = glob($migrationDir . '2024_*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Replace createIfNotExists back to create
    $content = str_replace("Schema::createIfNotExists(", "Schema::create(", $content);
    
    // Update the up() method to check if table exists
    $content = preg_replace(
        '/public function up\(\): void\s*\{/',
        "public function up(): void\n    {\n        if (Schema::hasTable(TABLE_NAME)) {\n            return;\n        }",
        $content
    );
    
    file_put_contents($file, $content);
}

echo "Migration files reset.\n";
