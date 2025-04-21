<?php
function getPhotos(?string $username = null): array {
    if ($username) {
        $url = "https://restful-api-photobooth-production.up.railway.app/photos.php?username=" . urlencode($username). "&apikey=0c2329c4a5a5c5d9996447d10f8506ce9401f801e0c836ffce1138d33ffe4526";
    } else {
        $url = "https://restful-api-photobooth-production.up.railway.app/photos.php";
    }
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_POST => false,
        CURLOPT_RETURNTRANSFER => true, // Capture response as a string
        CURLOPT_HTTPHEADER => [
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