<?php
$msg = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {

    $uploadfilename = $_FILES['fileUpload'];
    $uploadDir = __DIR__ . '/';
    $uploadFile = $uploadDir . basename($uploadfilename['name']);

    if (move_uploaded_file($uploadfilename['tmp_name'], $uploadFile)) {
        $msg[] = "File successfully uploaded!";
    } else {
        $msg[] = $uploadfilename['error'];
        $msg[] = "File upload failed!";
    }

    $msg[] = "<pre>";
    $files = listFilesInZip($uploadFile);


    foreach ($files as $k => $file) {
        //echo $file . " replaces " . $webroot ."/". $file . "\n";
        if (file_exists($webroot . "/" . $file)) {
            $msg[] = "OK";
        }
        unzipFile($uploadFile, $file, $webroot);
    }
    $msg[] = "</pre>";
    unlink($uploadFile);
}

function unzipFile($zipFilePath, $fileNameToExtract, $destinationPath)
{
    $zip = new ZipArchive;
    global $msg;
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



$commits = json_decode(file_get_contents("https://api.github.com/repos/MrIanC/WebMakerOne/commits?sha=main&per_page=1", false, stream_context_create(['http' => ['header' => "User-Agent: PHP\r\n"]])), true);
$verCtrl = $_SERVER['DOCUMENT_ROOT'] . "/setup/versionDate";
$gitDate = $commits[0]['commit']['author']['date'];
$currentDate = file_exists($verCtrl) ? file_get_contents($verCtrl) : "";
if ($gitDate == $currentDate) {
    $warning[] = "<div>Version: $gitDate</div>";
} else {
    $warning[] = "<div>Your version might be outdated, you may want to update.</div>";
}
//file_put_contents($verCtrl,$gitDate);
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
            <div class="display-1 fw-bold">Update</div>
        </div>
    </div>
    <div class="container">
        <div class="text-center">
            <?php
            echo implode($warning);
            ?>
        </div>
    </div>

    <div class="container">
        <h2>Update from GitHub</h2>
        <div class="text-center">
            <a href="?pluginpage=updateFromGit">Update From GitHub</a>
        </div>
    </div>
    <div class="container py-5 text-center">
        OR
    </div>

    <div class="container">
        <h2>Update from Zip</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="fileUpload">Choose a file:</label>
            <input type="file" id="fileUpload" name="fileUpload" accept=".zip">
            <br><br>
            <button class="btn btn-primary" type="submit">Upload and Update</button>
        </form>
    </div>
    <div>
        <?php echo implode($msg); ?>
    </div>
    <script>
    </script>
</body>

</html>