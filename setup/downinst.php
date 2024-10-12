<?php

$url = 'https://s8.techbit.co.za/install.zip'; // Replace with actual URL
$zipFile = __DIR__ . '/install.zip'; // Path to save the ZIP file

$fp = fopen($zipFile, 'w+'); // File pointer for writing the file

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
curl_setopt($ch, CURLOPT_BUFFERSIZE, 1024 * 1024); // Increase buffer size to 1MB
curl_setopt($ch, CURLOPT_TIMEOUT, 0); // No timeout for large files
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Set connection timeout to 10 seconds
curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024); // Minimum transfer speed 1KB/sec
curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 30); // If below speed limit for 30 seconds, abort

// Optional: Enable compression if server supports it
curl_setopt($ch, CURLOPT_ENCODING, '');

curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'File downloaded successfully!';
}

curl_close($ch);
fclose($fp);

$extractPath = $_SERVER['DOCUMENT_ROOT']; // Path to extract to (root directory in this case)

$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    // Extract the contents of the ZIP file
    $zip->extractTo($extractPath);
    $zip->close();
    echo 'File unzipped successfully!';
    
    // Optionally delete the ZIP file after extraction
    unlink($zipFile); 
} else {
    echo 'Failed to unzip the file!';
}