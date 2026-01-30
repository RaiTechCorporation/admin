<?php

$migrationDir = __DIR__ . '/database/migrations/';
$files = glob($migrationDir . '2024_*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Skip if already fixed
    if (strpos($content, "!Schema::hasTable(") !== false) {
        continue;
    }
    
    // Extract table name from Schema::create('tablename'
    if (preg_match("/Schema::create\('([^']+)'/", $content, $matches)) {
        $tableName = $matches[1];
        
        // Replace createIfNotExists with create (if it exists)
        $content = str_replace("Schema::createIfNotExists(", "Schema::create(", $content);
        
        // Add hasTable check before create
        $pattern = "/(\s+)(Schema::create\('" . preg_quote($tableName) . "',/";
        $replacement = '$1if (!Schema::hasTable(\'' . $tableName . '\')) {' . "\n" . '$1    $2' . "\n" . '$1}' . "\n";
        
        // More precise replacement
        $old = "        Schema::create('" . $tableName . "', function (Blueprint \$table) {";
        $new = "        if (!Schema::hasTable('" . $tableName . "')) {\n" .
               "            Schema::create('" . $tableName . "', function (Blueprint \$table) {";
        
        if (strpos($content, $old) !== false) {
            $content = str_replace($old, $new, $content);
            
            // Also need to close the if statement - find the closing brace of create and add }
            // Find the position where we close Schema::create
            $lines = explode("\n", $content);
            $inCreate = false;
            $braceCount = 0;
            $newLines = [];
            
            foreach ($lines as $i => $line) {
                if (strpos($line, "Schema::create('" . $tableName . "',") !== false) {
                    $inCreate = true;
                    $braceCount = 1;
                    $newLines[] = $line;
                } else if ($inCreate) {
                    $newLines[] = $line;
                    
                    // Count braces
                    $braceCount += substr_count($line, '{');
                    $braceCount -= substr_count($line, '}');
                    
                    // When braces balance out, close the if
                    if ($braceCount == 0) {
                        $inCreate = false;
                        $newLines[] = "        }";
                    }
                } else {
                    $newLines[] = $line;
                }
            }
            
            $content = implode("\n", $newLines);
            file_put_contents($file, $content);
            echo "Fixed: " . basename($file) . " (table: " . $tableName . ")\n";
        }
    }
}

echo "\nAll migration files fixed.\n";
