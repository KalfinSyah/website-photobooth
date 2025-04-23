<?php
function updateUsername(string $oldUsername, string $newUsername): array {
    $url = 'http://localhost/web-service/restful-api-photobooth/users.php';
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
