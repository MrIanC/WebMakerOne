<?php 
$filename = "$webroot/setup/plugins/build/build.txt";
if (file_exists($filename)) {
    unlink($filename);
}

header("location: /setup");