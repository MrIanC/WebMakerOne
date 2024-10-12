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

foreach (glob("$webroot/setup/plugins/users/*.json") as $value) {
    $exclusionList[] = str_replace($webroot, "", $value);
}

$zipList = [];
foreach (globrec("$webroot", $webroot) as $key => $file) {
    if (!in_array($file,$exclusionList)) {
        $zipList[] = $webroot . $file;
    }
}

