<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

// List of files to zip (can be in folders)
$baseDir = $webroot;

$files = listFilesInDirectory($baseDir);

function listFilesInDirectory($directory): array
{
    $files = [];
    $iterator = new DirectoryIterator($directory);
    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isDot()) {
            continue;
        }
        if ($fileinfo->isDir()) {
            if (!in_array(basename($fileinfo->getPathname()), ['setup'])) {
                $files = array_merge($files, listFilesInDirectory($fileinfo->getPathname()));
            }
        } else {
            $files[] = $fileinfo->getPathname();
        }
    }
    return $files;
}

$zipFileName = str_replace(".","_",$_SERVER['HTTP_HOST']) . ".zip";
$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    foreach ($files as $file) {
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