<?php
$fn = $_GET['filename'];
$uploads = $_SERVER['DOCUMENT_ROOT'] . "/uploads";
$filename = "$uploads/$fn";
$last = "";
$rr = false;

if (isset($_POST['rename'])) {
    if (isset($_POST['filename']) && isset($_POST['rename_filename'])) {
        rename("../uploads/{$_POST['filename']}", "../uploads/{$_POST['rename_filename']}");
    }
}

if (isset($_POST['resize'])) {
    if (isset($_POST['filename'])) {
        $fullpath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $_POST['filename'];
        $filename = basename($_POST['filename']);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'jfif', 'png'];
        if (in_array($extension, $allowed)) {
            if ($extension == "png") {
                $image = imagecreatefrompng($fullpath);
            } else {
                $image = imagecreatefromjpeg($fullpath); // For JPEG images. Use imagecreatefrompng() for PNG, etc.
            }


            $width = imagesx($image);
            $height = imagesy($image);
            $aspectRatio = $_POST['resize'];
            switch ($aspectRatio) {
                case '1x1': // Square crop (1:1)
                    $min_size = min($width, $height); // Find the smallest side
                    $crop_width = $min_size;
                    $crop_height = $min_size;
                    break;

                case '4x3': // Landscape crop (4:3)
                    if ($width / $height > 4 / 3) {
                        // Wider than 4:3, crop width
                        $crop_height = $height;
                        $crop_width = $height * 4 / 3;
                    } else {
                        // Taller than 4:3, crop height
                        $crop_width = $width;
                        $crop_height = $width * 3 / 4;
                    }
                    break;

                case '3x4': // Portrait crop (3:4)
                    if ($width / $height > 3 / 4) {
                        // Wider than 3:4, crop width
                        $crop_height = $height;
                        $crop_width = $height * 3 / 4;
                    } else {
                        // Taller than 3:4, crop height
                        $crop_width = $width;
                        $crop_height = $width * 4 / 3;
                    }
                    break;

                default:
                    throw new Exception("Invalid aspect ratio provided.");
            }

            // Calculate cropping coordinates (center the crop)
            $crop_x = round(($width - $crop_width) / 2, 0);
            $crop_y = round(($height - $crop_height) / 2, 0);

            $crop_width = round($crop_width, 0);
            $crop_height = round($crop_height, 0);

            // Create the destination image
            $cropped_image = imagecreatetruecolor($crop_width, $crop_height);

            // Copy and crop the image
            imagecopyresampled(
                $cropped_image, // Destination image
                $image, // Source image
                0,
                0, // Destination coordinates
                $crop_x,
                $crop_y, // Source coordinates
                $crop_width,
                $crop_height, // Destination width/height
                $crop_width,
                $crop_height // Source width/height
            );
            $cropped_image_path = str_replace($extension, "$aspectRatio.jpg", $fullpath); // Change to your desired output path
            imagejpeg($cropped_image, $cropped_image_path, 90); // Save as JPEG with quality 90
            imagedestroy($image);
            imagedestroy($cropped_image);
        }
        //rename("../uploads/{$_POST['filename']}", "../uploads/{$_POST['rename_filename']}");
    }
}

foreach (glob("$uploads/*") as $files) {
    if ($rr == true) {
        $next = $files;
        $rr = false;
        $nextbasename = basename($next);
        $links['next'] = "<a href=\"/setup/?pluginpage=mediaedit&filename=$nextbasename\"><img height=\"64px;\" src=\"/uploads/$nextbasename\" /></a>";

        $links['next'] = "
        <a  href=\"/setup/?pluginpage=mediaedit&filename=$nextbasename\" class=\"  col-1 text-center\" 
                    style=\"
            background-size:contain;
            background-position: center center;
            background-repeat:no-repeat;
            height:80vh; background-image:url('/uploads/$nextbasename');\"></a>
        ";
    }
    if ($fn == basename($files)) {
        if ($last != "") {
            $lastbasename = basename($last);
            $links['prev'] = "<a href=\"/setup/?pluginpage=mediaedit&filename=$lastbasename\"><img height=\"64px;\" src=\"/uploads/$lastbasename\" /></a>";
            $links['prev'] = "
            <a href=\"/setup/?pluginpage=mediaedit&filename=$lastbasename\" class=\"  col-1 text-center\"
            style=\"
            background-size:contain;
            background-position: center center;
            background-repeat:no-repeat;
            height:80vh; background-image: url('/uploads/$lastbasename');\"></a>
            ";
        }
        $rr = true;

    }
    $last = $files;
}

$links['prev'] ??= "";
$links['next'] ??= "";

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
        <div class="row align-items-center pt-5">
            <?php echo $links['prev'] ?>
            <?php echo "<div class=\"col-10 text-center\" style=\"
            background-size:contain;
            background-position: center center;
            background-repeat:no-repeat;
            height:80vh; background-image: url('/uploads/$fn');\"/></div>"; ?>
            <?php echo $links['next'] ?>
        </div>
        <div>
            <?php
            
            $imageSize = getimagesize($filename);
            if ($imageSize) {
                $width = $imageSize[0]; // Image width
                $height = $imageSize[1]; // Image height
            
                // Calculate aspect ratio
                $aspectRatio = $width / $height;

                echo "Width: $width pixels\n";
                echo "Height: $height pixels\n";
                echo "Aspect Ratio: $aspectRatio\n";
            } else {
                echo "Could not retrieve image size.";
            }

            ?>
        </div>
        <div class="row">
            <div class="col">
                <form method="post">
                    <div>
                        <input class="form-control" type="text" name="rename_filename" value="<?php echo $fn ?>" />
                    </div>
                    <div class="py-3 small">
                        <input type="hidden" name="filename" value="<?php echo $fn ?>" />
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-warning" type="submit" name="rename"
                                title="Rename">Ren</button>
                            <button class="btn btn-sm btn-info" type="submit" name="resize" value="1x1"
                                title="1to1">1:1</button>
                            <button class="btn btn-sm btn-info" type="submit" name="resize" value="4x3"
                                title="4by3">4:3</button>
                            <button class="btn btn-sm btn-info" type="submit" name="resize" value="3x4"
                                title="3by4">3:4</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>