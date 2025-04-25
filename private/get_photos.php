<?php
require_once __DIR__ . '/load_env.php';
function getPhotos(?string $username = null): array {
    if ($username) {
        $url = $_ENV['BASEURLAPI'] . "/photos.php?username=" . urlencode($username);
    } else {
        $url = $_ENV['BASEURLAPI'] . "/photos.php";
    }
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_POST => false,
        CURLOPT_RETURNTRANSFER => true, // Capture response as a string
        CURLOPT_HTTPHEADER => [
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