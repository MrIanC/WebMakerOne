<?php
$list = [];
$uploadInfo = [];

if (isset($_POST['submit'])) {
    // Check if multiple files are uploaded
    if (isset($_FILES['uploadedFiles'])) {
        $uploadDir = '../uploads/'; // Define the upload directory

        // Loop through each uploaded file
        foreach ($_FILES['uploadedFiles']['name'] as $key => $fileName) {
            $fileTmpPath = $_FILES['uploadedFiles']['tmp_name'][$key];
            $fileSize = $_FILES['uploadedFiles']['size'][$key];
            $fileType = $_FILES['uploadedFiles']['type'][$key];
            $fileError = $_FILES['uploadedFiles']['error'][$key];

            // Only proceed if there was no error during upload
            if ($fileError == 0) {
                
                // Create a hash of the file for unique file naming
                $hash_md5 = md5_file($fileTmpPath);
                // Get the file extension
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                // Create a unique name for the file to avoid overwriting existing files
                $newFileName = $hash_md5 . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;

                // Check if the file already exists
                if (!file_exists($destination)) {
                    // Move the file to the destination
                    
                    if (move_uploaded_file($fileTmpPath, $destination)) {
                        $uploadInfo[] = "The file <strong>$fileName</strong> has been uploaded successfully!";
                        $uploadInfo[] = "File name: " . $newFileName;
                        $uploadInfo[] = "File type: " . $fileType;
                        $uploadInfo[] = "File size: " . $fileSize . " bytes<br>";
                        if ($fileName == "favicon.ico") {
                            file_put_contents($_SERVER['DOCUMENT_ROOT'] ."/favicon.ico", file_get_contents($destination));
                            $uploadInfo[] = "A Favicon has been detected and been put in the web-root";
                        }
                    } else {
                        $uploadInfo[] = "There was an error moving the file <strong>$fileName</strong>.";
                    }
                } else {
                    $uploadInfo[] = "The file <strong>$fileName</strong> already exists.";
                    $uploadInfo[] = "If you are sure it hasn't been uploaded before, rename the file.";
                }
            } else {
                $uploadInfo[] = "Error uploading file <strong>$fileName</strong>: Error code " . $fileError;
            }
        }
    } else {
        $uploadInfo[] = "No files uploaded.";
    }
}

// Output the result messages
foreach ($uploadInfo as $message) {
    echo $message . "<br>";
}

if (isset($_POST['delete'])) {
    if (isset($_POST['filename'])) {
        unlink("../uploads/{$_POST['filename']}");
    }
}

if (isset($_POST['rename'])) {
    if (isset($_POST['filename']) && isset($_POST['rename_filename'])) {
        rename("../uploads/{$_POST['filename']}", "../uploads/{$_POST['rename_filename']}");
    }
}

$files = glob("../uploads/*");

foreach ($files as $key => $value) {
    $filename = basename($value);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    switch ($extension) {
        case "jpg":
        case "jpeg":
        case "png":
        case "ico":
        case "jfif":
            $filecontent = "<a  href=\"/setup/?pluginpage=mediaedit&filename=$filename\"><img height=\"64px;\" src=\"/uploads/$filename\" /></a>";
            break;
        default:
            $filecontent = "<a class=\"btn btn-dark rounded-0\" style=\"height:64px;\"  href=\"/uploads/$filename\">No Thumbnail</a>";
            break;

    }
    $list[] = "
    <div class=\"col-12 col-sm-6 col-md-4 col-lg-3\">
        <div class=\"border rounded shadow p-3 m-3\">
            <form method=\"post\">
                <div>
                    <input class=\"form-control\" type=\"text\" name=\"rename_filename\" value=\"$filename\" />
                </div>
                <div class=\"py-3 text-center\">
                    $filecontent
                </div>
                <div class=\"py-3 small\">
                    <input type=\"hidden\" name=\"filename\" value=\"$filename\" />
                    <div class=\"d-flex justify-content-between\">
                        <button class=\"btn btn-sm btn-warning\"type=\"submit\" name=\"rename\" title=\"Rename\">Ren</button>
                        <button class=\"btn btn-sm btn-danger\"type=\"submit\" name=\"delete\" title=\"Delete\">Del</button>
                    </div>
                </div>            
            </form>
        </div>
    </div>
    ";
}

function generateUUID()
{
    // Get current date in Ymd format
    $date = date('Ymds');
    $time = date("Hi");
    // Generate the rest of the UUID (random part)
    $uuid = sprintf(
        '%s-%s-%04x-%04x-%04x-%04x%04x%04x',
        $date,                                     // Use the date as the first part
        $time,   // 4 random digits
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,               // 4 random digits starting with 4 (UUID version)
        mt_rand(0, 0x3fff) | 0x8000,               // 4 random digits starting with 8, 9, A, or B
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff) // 12 random digits
    );

    return $uuid;
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
    <script id="jsonld" type="application/ld+json"></script>
</head>

<body>
    <?php include "menu.php"; ?>
    <div class="container">
        <div class="text-center">
            <h2 class="display-1 fw-bold">Media Manager</h2>
        </div>
        <div class="p-3 m-3 border rounded shadow">
            <div class="display-4 border-bottom mb-3">File Upload</div>
            <div class="row">
                <div class="col">
                    <ul>
                        <li>Upload a file that will be used in the website.</li>
                        <li>This upload tool does not check compatibility.</li>
                        <li>The file upload size has a size limit of 50MB</li>
                        <li>If you don't use it then delete it</li>
                        <li>If you don't use it then delete it</li>
                    </ul>
                </div>
                <div class="col">
                    <ul>
                        <?php
                        foreach ($uploadInfo as $message) {
                            echo $message . "<br>";
                        }
                        ?>
                    </ul>

                    <form method="POST" enctype="multipart/form-data">
                        <label for="file">Choose a file to upload:</label>
                        <input class="form-control mb-3" type="file" name="uploadedFiles[]" id="file" multiple>
                        <input class="btn btn-primary" type="submit" name="submit" value="Upload">
                    </form>
                </div>
            </div>
        </div>

        <div>
        </div>
        <div class="display-4 border-bottom mb-3">Uploaded Files</div>
        <div class="row">
            <?php echo implode($list); ?>
        </div>
        <div class="row">
            <div class="col">

            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>