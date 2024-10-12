<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

$url = 'https://github.com/MrIanC/WebMakerOne/archive/refs/heads/main.zip';
$zipFile = __DIR__ . '/install.zip';

$fp = fopen($zipFile, 'w+');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_BUFFERSIZE, 1024 * 1024);
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024);
curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 30);
curl_setopt($ch, CURLOPT_ENCODING, '');

curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'File downloaded successfully!';
}

curl_close($ch);
fclose($fp);

$extractPath = $_SERVER['DOCUMENT_ROOT'];

$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $filePath = $zip->getNameIndex($i);
        $mainFolder = strpos($filePath, '/') !== false ? explode('/', $filePath)[0] . '/' : '';
        $newPath = str_replace($mainFolder, '', $filePath); 
        if (substr($filePath, -1) == '/') {
            @mkdir("$extractPath/$newPath", 0755, true);
        } else {
            echo "extracting: $extractPath/$newPath </br>";
            copy("zip://$zipFile#$filePath", "$extractPath/$newPath");
        }
    }
    $zip->close();
    unlink($zipFile);
    echo 'Files unzipped successfully!';
} else {
    echo 'Failed to unzip the file!';
}