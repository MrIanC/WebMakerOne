<?php
$actionFilesPath = "$webroot/resources/js/action/*.js";

if (isset($_POST['submit'])) {
    // Check if the file was uploaded without errors
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] == 0) {
        $fileName = $_FILES['uploadedFile']['name'];
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $hash_md5 = md5_file($fileTmpPath);
        // Get the file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Define upload directory
        $uploadDir = "$webroot/resources/js/action/";

        // Create a unique name for the file to avoid overwriting existing files
        $newFileName = basename($fileName);
        $destination = $uploadDir . $newFileName;

        if (!file_exists($destination)) {
            // Move the file to the destination
            if (move_uploaded_file($fileTmpPath, $destination)) {
                $uploadInfo[] = "The file has been uploaded successfully!";
                $uploadInfo[] = "<br>File name: " . $newFileName;
                $uploadInfo[] = "<br>File type: " . $fileType;
                $uploadInfo[] = "<br>File size: " . $fileSize . " bytes";
            } else {
                $uploadInfo[] = "There was an error moving the file.";
            }
        } else {
            $uploadInfo[] = "This file has already been uploaded.";
            $uploadInfo[] = "If you are 100% sure that it hasn't then rename the file uploaded with the name <strong>$newFileName</strong>";
        }
    } else {
        $uploadInfo[] = "Error: " . $_FILES['uploadedFile']['error'];
    }
} else {
    $uploadInfo = [];
}

if (isset($_POST['delete'])) {
    if (isset($_POST['filename'])) {
        unlink("$webroot/resources/js/action/{$_POST['filename']}");
    }
}

if (isset($_POST['rename'])) {
    if (isset($_POST['filename']) && isset($_POST['rename_filename'])) {
        rename("$webroot/resources/js/action/{$_POST['filename']}","$webroot/resources/js/action/{$_POST['rename_filename']}");
    }
}

$files = glob("$webroot/resources/js/action/*.js");

foreach ($files as $key => $value) {
    $filename = basename($value);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    switch ($extension) {
        case "js":
            $filecontent = "<a class=\"btn btn-dark rounded-0\" style=\"height:64px;\" target=\"_BLANK\" href=\"/resources/js/action/$filename\">View Code</a>";
            break;
        default:
            $filecontent = "<a class=\"btn btn-dark rounded-0\" style=\"height:64px;\" target=\"_BLANK\" href=\"/uploads/$filename\">No Thumbnail</a>";
            break;

    }
    $list[] = "
    <div class=\"col-12 col-sm-12 col-md-6 col-lg-4\">
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script id="jsonld" type="application/ld+json"></script>
</head>

<body>
<?php include "menu.php"; ?>
    <div class="container">
        <div class="text-center">
            <h2 class="display-1 fw-bold">Action Script Manager</h2>
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
                    </ul>
                </div>
                <div class="col">
                <ul>
                        <?php
                        foreach ($uploadInfo as $k => $value) {
                            echo "<li>$value</li>";
                        }
                        ?>
                    </ul>

                    <form method="POST" enctype="multipart/form-data">
                        <label for="file">Choose a file to upload:</label>
                        <input class="form-control mb-3" type="file" name="uploadedFile" id="file">
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