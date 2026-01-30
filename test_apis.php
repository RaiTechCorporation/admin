<?php

$baseUrl = 'http://127.0.0.1:8000/api';
$apiKey = 'retry123';
$testResults = [];

function testEndpoint($method, $endpoint, $data = [], $requireAuth = false, $authToken = '') {
    global $baseUrl, $apiKey, $testResults;
    
    $url = $baseUrl . $endpoint;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $headers = [
        'APIKEY: ' . $apiKey,
        'Content-Type: application/json',
    ];
    
    if ($requireAuth && $authToken) {
        $headers[] = 'AUTHTOKEN: ' . $authToken;
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    $result = [
        'endpoint' => $endpoint,
        'method' => $method,
        'http_code' => $httpCode,
        'success' => ($httpCode >= 200 && $httpCode < 300),
        'response' => json_decode($response, true),
        'error' => $error
    ];
    
    $testResults[] = $result;
    
    return $result;
}

echo "====================================\n";
echo "API Testing Started\n";
echo "====================================\n\n";

echo "[1] Testing Server Connection...\n";
$connTest = testEndpoint('POST', '/user/logInUser', [
    'identity' => 'test',
    'device_token' => 'test',
    'device' => 'test',
    'login_method' => 'test',
    'fullname' => 'Test User'
]);

if ($connTest['http_code'] > 0) {
    echo "✓ Server is running\n";
    echo "  HTTP Code: " . $connTest['http_code'] . "\n";
} else {
    echo "✗ Server is NOT running\n";
    echo "  Error: " . $connTest['error'] . "\n";
    echo "\nPlease ensure the Laravel server is running:\n";
    echo "  php artisan serve\n";
    exit(1);
}

echo "\n[2] Testing Critical Endpoints...\n";

$testCases = [
    [
        'name' => 'User Login (logInUser)',
        'method' => 'POST',
        'endpoint' => '/user/logInUser',
        'data' => [
            'fullname' => 'Test User',
            'identity' => 'test_' . time(),
            'device_token' => 'test_token_' . time(),
            'device' => 'test_device',
            'login_method' => 'manual'
        ],
        'requireAuth' => false
    ],
    [
        'name' => 'Settings Fetch (fetchSettings)',
        'method' => 'POST',
        'endpoint' => '/settings/fetchSettings',
        'data' => [],
        'requireAuth' => false
    ],
];

$authToken = '';

foreach ($testCases as $test) {
    echo "\n  Testing: " . $test['name'] . "\n";
    $result = testEndpoint($test['method'], $test['endpoint'], $test['data'], $test['requireAuth'], $authToken);
    
    if ($result['success']) {
        echo "    ✓ SUCCESS (HTTP " . $result['http_code'] . ")\n";
        if (isset($result['response']['data']['token'])) {
            $authToken = $result['response']['data']['token'];
            echo "    Token obtained for further tests\n";
        }
    } else {
        echo "    ✗ FAILED (HTTP " . $result['http_code'] . ")\n";
        if ($result['error']) {
            echo "    Error: " . $result['error'] . "\n";
        } elseif (isset($result['response']['message'])) {
            echo "    Message: " . $result['response']['message'] . "\n";
        }
    }
}

echo "\n[3] Summary\n";
echo "====================================\n";

$successCount = count(array_filter($testResults, function($r) { return $r['success']; }));
$failureCount = count($testResults) - $successCount;

echo "Total Tests: " . count($testResults) . "\n";
echo "Passed: " . $successCount . " ✓\n";
echo "Failed: " . $failureCount . " ✗\n";

if ($failureCount > 0) {
    echo "\nFailed Tests:\n";
    foreach ($testResults as $result) {
        if (!$result['success']) {
            echo "  - " . $result['endpoint'] . " (HTTP " . $result['http_code'] . ")\n";
        }
    }
}

echo "\n====================================\n";
echo "Testing Complete\n";
echo "====================================\n";
