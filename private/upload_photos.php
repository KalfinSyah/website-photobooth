<?php
function uploadPhotos(string $username, string $photo): array {
    $url = 'http://localhost/restful-api-photobooth/photos.php';

    // Extract MIME type and image data from the data URL
    if (!preg_match('/^data:image\/(\w+);base64,/', $photo, $matches)) {
        die('Invalid image data URL.');
    }
    $imageType = $matches[1];
    $imageData = base64_decode(substr($photo, strpos($photo, ',') + 1));

    // Create a temporary file
    $tempFile = tmpfile();
    fwrite($tempFile, $imageData);
    $tempFilePath = stream_get_meta_data($tempFile)['uri'];

    // Generate filename with username and date
    $sanitizedUsername = preg_replace('/[^a-zA-Z0-9_-]/', '_', $username);
    $dateString = date('Ymd_His'); // Format: YearMonthDay_HourMinuteSecond
    $fileName = "{$sanitizedUsername}_{$dateString}.{$imageType}";

    // Prepare the photo as a CURLFile
    $photoFile = new CURLFile($tempFilePath, "image/$imageType", $fileName);

    $data = [
        'username' => $username,
        'photo' => $photoFile,
    ];

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
            "apikey: 716ed4002e5f1ddcaa91a5a19e57ccb0d6d7087d7590274fea196493fba0f2d1"
        ]
    ]);
    $response = curl_exec($curl);
    $error = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Close and remove the temporary file
    fclose($tempFile);

    if ($response === false) {
        die("CURL Error: $error");
    }

    return json_decode($response, true);
}