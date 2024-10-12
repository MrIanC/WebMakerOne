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
    $msg[] = 'Error: ' . curl_error($ch);
} else {
    $msg[] = 'File downloaded successfully!';
}

curl_close($ch);
fclose($fp);

function unzipFile($zipFilePath, $fileNameToExtract, $destinationPath)
{
    global $msg;
    $zip = new ZipArchive;

    // Open the ZIP file
    if ($zip->open($zipFilePath) === TRUE) {
        // Check if the file exists in the ZIP archive
        if ($zip->locateName($fileNameToExtract) !== false) {
            // Extract the specific file to the destination path
            $zip->extractTo($destinationPath, $fileNameToExtract);
            $msg[] = "File '$fileNameToExtract' extracted to '$destinationPath'.\n";
        } else {
            $msg[] = "File '$fileNameToExtract' not found in the ZIP archive.\n";
        }
        // Close the ZIP file
        $zip->close();
    } else {
        $msg[] = "Failed to open ZIP file.\n";
    }
}

function listFilesInZip($zipFilePath)
{
    global $msg;
    $files = [];
    $zip = new ZipArchive;

    // Open the ZIP file
    if ($zip->open($zipFilePath) === TRUE) {
        // Loop through each file in the ZIP
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $file = $zip->getNameIndex($i);
            $files[] = $file;
        }

        // Close the ZIP file
        $zip->close();
    } else {
        $msg[] = "Failed to open ZIP file.\n";
    }
    return $files;
}
function globrec($path, $webroot)
{
    $files = [];
    foreach (glob("$path/*") as $key => $value) {
        if (is_dir($value)) {
            $files = array_merge($files, globrec($value, $webroot));
        } else {
            $files[] = str_replace($webroot, "", $value);
        }
    }
    return $files;
}


foreach (glob("$webroot/setup/plugins/users/credentials/*.php") as $value) {
    $exclusionList[] = str_replace($webroot, "", $value);
}

$exclusionList[] = '/setup/index/state.json';
$exclusionList[] = '/setup/plugins/ai/settings.php';
$exclusionList[] = '/setup/plugins/fonts/settings.json';
$exclusionList[] = '/setup/plugins/palette/settings.json';
$exclusionList[] = '/setup/path.php';

$replaceList = [];
foreach (globrec("$webroot/setup", $webroot) as $key => $file) {
    if (!in_array($file, $exclusionList)) {
        $replaceList[] = $webroot . $file;
    }
}

foreach (globrec("$webroot/resources/js", $webroot) as $key => $file) {
    $replaceList[] = $webroot . $file;
}
foreach (globrec("$webroot/resources/js/actions", $webroot) as $key => $file) {
    $replaceList[] = $webroot . $file;
}

$msg = [];
$allfiles = listFilesInZip($zipFile);
foreach ($allfiles as $filename) {
    if (str_contains($filename, "WebMakerOne-main/setup")) {
        $destinationPath = $_SERVER['DOCUMENT_ROOT'];//dirname(str_replace("WebMakerOne-main", , $filename));
        unzipFile($zipFile, $filename, $destinationPath);
        $msg[] = "$filename<br>";
    }
    if (str_contains($filename, "WebMakerOne-main/resources/js/")) {
        $destinationPath = $_SERVER['DOCUMENT_ROOT'];//dirname(str_replace("WebMakerOne-main", , $filename));
        unzipFile($zipFile, $filename, $destinationPath);
        $msg[] = "$filename<br>";
    }
}

unlink($zipFile);
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
        <p>Files Updated</p>
        <?php $msg[] = implode($msg); ?>
    </div>
</body>

</html>