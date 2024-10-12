<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {

    $uploadfilename = $_FILES['fileUpload'];
    $uploadDir = __DIR__ . '/';
    $uploadFile = $uploadDir . basename($uploadfilename['name']);

    if (move_uploaded_file($uploadfilename['tmp_name'], $uploadFile)) {
        echo "File successfully uploaded!";
    } else {
        echo "File upload failed!";
    }

    echo "<pre>";
    $files = listFilesInZip($uploadFile);


    foreach ($files as $k => $file) {
        //echo $file . " replaces " . $webroot ."/". $file . "\n";
        if (file_exists($webroot . "/" . $file)) {
            echo "OK";
        }
        unzipFile($uploadFile, $file, $webroot);
    }
    echo "</pre>";
    unlink($uploadFile);
}

function unzipFile($zipFilePath, $fileNameToExtract, $destinationPath)
{
    $zip = new ZipArchive;

    // Open the ZIP file
    if ($zip->open($zipFilePath) === TRUE) {
        // Check if the file exists in the ZIP archive
        if ($zip->locateName($fileNameToExtract) !== false) {
            // Extract the specific file to the destination path
            $zip->extractTo($destinationPath, $fileNameToExtract);
            echo "File '$fileNameToExtract' extracted to '$destinationPath'.\n";
        } else {
            echo "File '$fileNameToExtract' not found in the ZIP archive.\n";
        }
        // Close the ZIP file
        $zip->close();
    } else {
        echo "Failed to open ZIP file.\n";
    }
}

function listFilesInZip($zipFilePath)
{
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
        echo "Failed to open ZIP file.\n";
    }
    return $files;
}
?>
<!DOCTYPE html>
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
            <div class="display-1 fw-bold">Upload Installation Update</div>
        </div>
        <form method="post" enctype="multipart/form-data">
            <label for="fileUpload">Choose a file:</label>
            <input type="file" id="fileUpload" name="fileUpload" accept=".zip">
            <br><br>
            <button class="btn btn-primary" type="submit">Upload and Update</button>
        </form>
    </div>
    <script>
    </script>
</body>

</html>