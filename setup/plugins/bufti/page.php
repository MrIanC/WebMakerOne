<?php
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
    if (!in_array($file,$exclusionList)) {
        $replaceList[] = $webroot . $file;
    }
}

foreach (globrec("$webroot/resources/js",$webroot) as $key =>$file) {
    $replaceList[] = $webroot . $file;
}
foreach (globrec("$webroot/resources/js/actions",$webroot) as $key =>$file) {
    $replaceList[] = $webroot . $file;
}

//print_r($replaceList);
//die();

ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

// List of files to zip (can be in folders)
$baseDir = $webroot;

$zipFileName = str_replace(".","_",$_SERVER['HTTP_HOST']) . "_BUFTI.zip";
$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    foreach ($replaceList as $file) {
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