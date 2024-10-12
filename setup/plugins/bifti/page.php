<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);
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

//User Accounts. Only accept admin as user and exclude all of the rest
$exclusionList[] = '/setup/plugins/ai/settings.php';
$exclusionList[] = '/setup/path.php';

foreach (glob("$webroot/setup/plugins/users/credentials/*.php") as $value) {
    $exclusionList[] = str_replace($webroot, "", $value);
}

$backup = glob("$webroot/resources/parts/*.bak");
foreach ($backup as $key => $value) {
    $exclusionList[] = str_replace($webroot, "", $value);
}
$backup = glob("$webroot/resources/parts/pages/*.bak");
foreach ($backup as $key => $value) {
    $exclusionList[] = str_replace($webroot, "", $value);
}

$zipList = [];
foreach (globrec("$webroot", $webroot) as $key => $file) {
    if (!in_array($file, $exclusionList)) {
        $zipList[] = $webroot . $file;
    }
}

ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

// List of files to zip (can be in folders)
$baseDir = $webroot;

$zipFileName = str_replace(".", "_", $_SERVER['HTTP_HOST']) . "_BIFTI.zip";
$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    foreach ($zipList as $file) {
        if (file_exists($file)) {
            $localPath = str_replace($baseDir . "/", '', $file);
            $zip->addFile($file, $localPath);
        }
    }

    // Close the ZIP archive
    $zip->close();

    // Set headers to prompt download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
    header('Content-Length: ' . filesize($zipFileName));

    // Read the ZIP file to output it to the browser
    readfile($zipFileName);

    // Delete the temporary ZIP file after download
    unlink($zipFileName);

    exit;
} else {
    echo 'Failed to create ZIP file.';
}
