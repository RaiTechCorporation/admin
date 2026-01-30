<?php

$baseUrl = 'http://127.0.0.1:8000/api';
$apiKey = 'retry123';

function testEndpoint($method, $endpoint, $data = [], $headers = []) {
    global $baseUrl, $apiKey;
    
    $url = $baseUrl . $endpoint;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $defaultHeaders = [
        'APIKEY: ' . $apiKey,
        'Content-Type: application/json',
    ];
    
    $allHeaders = array_merge($defaultHeaders, $headers);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
    
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'url' => $url,
        'http_code' => $httpCode,
        'response' => $response,
        'response_json' => json_decode($response, true),
        'error' => $error
    ];
}

echo "====================================\n";
echo "API Testing - Detailed View\n";
echo "====================================\n\n";

echo "[TEST 1] Settings Fetch (Should work without auth)\n";
echo "Endpoint: POST /settings/fetchSettings\n\n";
$result = testEndpoint('POST', '/settings/fetchSettings', []);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response:\n";
echo json_encode($result['response_json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

echo "[TEST 2] User Login (logInUser)\n";
echo "Endpoint: POST /user/logInUser\n";
echo "Data: fullname, identity, device_token, device, login_method\n\n";

$loginData = [
    'fullname' => 'Test User',
    'identity' => 'test_' . time(),
    'device_token' => 'test_token_' . time(),
    'device' => 'test_device',
    'login_method' => 'manual'
];

echo "Request Data:\n";
echo json_encode($loginData, JSON_PRETTY_PRINT) . "\n\n";

$result = testEndpoint('POST', '/user/logInUser', $loginData);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response:\n";
echo json_encode($result['response_json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

if ($result['http_code'] == 500) {
    echo "⚠ 500 Error Detected!\n";
    echo "Raw Response:\n";
    echo $result['response'] . "\n\n";
}

echo "[TEST 3] Check API Key\n";
echo "Testing with invalid API key...\n";
$result = testEndpoint('POST', '/settings/fetchSettings', [], ['APIKEY: invalid_key']);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . $result['response'] . "\n\n";

echo "[TEST 4] Check without API Key\n";
echo "Testing without API key...\n";

$url = $baseUrl . '/settings/fetchSettings';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n\n";

echo "[TEST 5] All Available API Endpoints\n";
echo "====================================\n";

$endpoints = [
    '/user/logInUser',
    '/user/logInFakeUser',
    '/user/logOutUser',
    '/user/updateUserDetails',
    '/user/checkUsernameAvailability',
    '/user/blockUser',
    '/user/followUser',
    '/user/searchUsers',
    '/post/addPost_Feed_Text',
    '/post/likePost',
    '/post/disLikePost',
    '/post/addPostComment',
    '/post/fetchPostById',
    '/post/fetchUserPosts',
    '/post/deletePost',
    '/misc/sendGift',
    '/misc/reportPost',
    '/settings/fetchSettings',
];

echo "Testing " . count($endpoints) . " endpoints for availability...\n\n";

$results_summary = [];

foreach ($endpoints as $endpoint) {
    $result = testEndpoint('POST', $endpoint, []);
    $status = ($result['http_code'] < 500) ? '✓' : '✗';
    $results_summary[] = [
        'endpoint' => $endpoint,
        'http_code' => $result['http_code'],
        'status' => $status
    ];
    echo $status . " " . $endpoint . " - HTTP " . $result['http_code'] . "\n";
}

echo "\n====================================\n";
echo "Summary\n";
echo "====================================\n";

$working = count(array_filter($results_summary, function($r) { return $r['http_code'] < 500; }));
$broken = count(array_filter($results_summary, function($r) { return $r['http_code'] >= 500; }));

echo "Working Endpoints: " . $working . " ✓\n";
echo "Broken Endpoints (500+): " . $broken . " ✗\n";

if ($broken > 0) {
    echo "\nBroken Endpoints:\n";
    foreach ($results_summary as $r) {
        if ($r['http_code'] >= 500) {
            echo "  - " . $r['endpoint'] . " (HTTP " . $r['http_code'] . ")\n";
        }
    }
}

echo "\n";
