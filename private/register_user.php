<?php
function registerUser(string $username, string $password, string $re_enter_password): array {
    $url = 'http://localhost/web-service/restful-api-photobooth/users.php';
    $data = [
        'username' => $username,
        'password' => $password,
        're_enter_password' => $re_enter_password
    ];
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true, // Capture response as a string
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
            "apikey: 312de9777bff309a1a6cc1b1f5838f2ec514992703438a692ac8f1859e82a5a0"
        ]
    ]);
    $response = curl_exec($curl);
    if ($response === false) {
        die('Error: ' . curl_error($curl));
    }
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($httpCode >= 400) {
        die("API request failed with HTTP code $httpCode");
    }
    curl_close($curl);
    return json_decode($response, true);
}