<?php

$migrationDir = __DIR__ . '/database/migrations/';
$files = glob($migrationDir . '2024_*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, "Schema::create(") !== false) {
        $updated = str_replace("Schema::create(", "Schema::createIfNotExists(", $content);
        file_put_contents($file, $updated);
        echo "Updated: " . basename($file) . "\n";
    }
}

echo "\nAll migrations updated.\n";
