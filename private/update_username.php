<?php
function updateUsername(string $oldUsername, string $newUsername): array {
    $url = 'http://localhost/restful-api-photobooth/users.php';
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
            "apikey: 0c2329c4a5a5c5d9996447d10f8506ce9401f801e0c836ffce1138d33ffe4526"
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
