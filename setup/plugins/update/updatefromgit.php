<?php
$msg[] = "This is not yet working!";
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
    $msg[] = 'Error: ' . curl_error($ch);
} else {
    $msg[] = 'File downloaded successfully!';
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
        $filepathOK = false;

        if (str_contains($filePath, "setup/"))
            $filepathOK = true;
        if (str_contains($filePath, "resources/js/"))
            $filepathOK = true;

        if ($filepathOK) {
            if (substr($filePath, -1) == '/') {
                @mkdir("$extractPath/$newPath", 0755, true);
            } else {
                $msg[] = "extracting: $extractPath/$newPath </br>";
                copy("zip://$zipFile#$filePath", "$extractPath/$newPath");
            }
        }

    }
    $zip->close();
    unlink($zipFile);
    $msg[] = 'Files unzipped successfully!';
} else {
    $msg[] = 'Failed to unzip the file!';
}

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="no-index, no-follow">
    <meta name="favicon" content="favicon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/resources/css/fonts.css" rel="stylesheet">
    <style>
    </style>
</head>

<body>
    <?php include "menu.php"; ?>
    <div class="container">
        <div class="text-center">
            <div class="display-1 fw-bold">Update</div>
        </div>
        <p>Lets Check!</p>
        <?php $msg[] = implode($msg); ?>
    </div>
</body>

</html>