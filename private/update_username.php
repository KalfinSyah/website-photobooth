<?php
require_once __DIR__ . '/load_env.php';
function updateUsername(string $oldUsername, string $newUsername): array {
    $url = $_ENV['BASEURLAPI'] . '/users.php';
    $data = [
        'old_username' => $oldUsername,
        'new_username' => $newUsername
    ];
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query($data), // Encode data for PUT
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/x-www-form-urlencoded",
            "apikey: " . $_ENV['APIKEY']
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
