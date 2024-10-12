<?php

ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);
$error[] = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Directory where the file will be uploaded
    $targetDirectory = "../resources/fonts/";

    // Full path for the file
    $targetFile = $targetDirectory . basename($_FILES["uploadedFile"]["name"]);

    // Flag for file upload success
    $uploadOk = 1;

    // Get the file extension
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check file size (limit: 5MB)
    if ($_FILES["uploadedFile"]["size"] > 5000000) {
        $error[] = "Sorry, your file is too large.<br>";
        $uploadOk = 0;
    }

    // Allow only TTF file format
    if ($fileType != "ttf") {
        $error[] = "Sorry, only TTF files are allowed.<br>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        $error[] = "Sorry, file already exists.<br>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 (error occurred)
    if ($uploadOk == 0) {
        $error[] = "Sorry, your file was not uploaded.<br>";
    } else {
        // Try to upload the file
        if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $targetFile)) {
            $error[] = "The file " . htmlspecialchars(basename($_FILES["uploadedFile"]["name"])) . " has been uploaded.<br>";
        } else {
            $error[] = "Sorry, there was an error uploading your file.<br>";
        }
    }
}

$settings = [];

if (file_exists(filename: __DIR__ . '/settings.json')) {
    $settings = json_decode(json: file_get_contents(filename: __DIR__ . '/settings.json'), associative: true);
} else {
    
    $settings['fonts'] = [];
}

if (!empty($_GET['headings']) && !empty($_GET['paragraphs']) && !empty($_GET['navigation'])) {

} else {
    $_GET = $settings['fonts'];
}

if (!empty($_GET['headings']) && !empty($_GET['paragraphs']) && !empty($_GET['navigation'])) {
    $settings['fonts'] = $_GET;
    file_put_contents(filename: __DIR__ . '/settings.json', data: json_encode(value: $settings, flags: JSON_PRETTY_PRINT));

    $css = "

    @font-face {
        font-family: 'headingFonts';
        src: url('/resources/fonts/{$_GET['headings']}') format('truetype');
    }       
    @font-face {
        font-family: 'paragraphFonts';
        src: url('/resources/fonts/{$_GET['paragraphs']}') format('truetype');
    }
    @font-face {
        font-family: 'navigationFonts';
        src: url('/resources/fonts/{$_GET['navigation']}') format('truetype');
    }

    * {
            font-family: \"paragraphFonts\", serif;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: \"headingFonts\", sans-serif !important;
    }

    nav * {
        font-family: \"navigationFonts\", sans-serif  !important;;
    }   
    ";
    file_put_contents("../resources/css/fonts.css", $css);
}
$css = [];
foreach (glob(pattern: "../resources/fonts/*.ttf") as $filename) {
    $fonts[] = "<option value=\"" . basename(path: $filename) . "\">" . basename(path: $filename) . "</option>";
    $fontName = str_replace([".ttf", " "], ["", ""], basename(path: $filename));
    $examples[] = "<div class=\"d-flex justify-content-between\"><div data-cssfont=\"$fontName\" data-fontfamily=\"" . basename(path: $filename) . "\" class=\"usefont btn btn-sm btn-primary mb-2\">Use</div><div class=\"" . $fontName . "\">" . $fontName . ": The Quick red fox jumped over the lazy brown cow. </div></div>";
    $css[] = "@font-face {
        font-family: '$fontName';
        src: url('/resources/fonts/" . basename(path: $filename) . "') format('truetype');
    }       
    .$fontName {
        font-family: \"$fontName\", serif;
        font-size:20px;  
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/resources/css/fonts.css" rel="stylesheet">
    <style>
        <?php echo implode($css); ?>
        .upload-btn {
            cursor: pointer;
            border: none;
            background: none;
        }

        /* Default SVG for upload */
        .upload-icon {
            width: 50px;
            height: 50px;
        }

        /* Change button appearance when file is attached */
        .upload-btn.attached .upload-icon {
            fill: green;
        }
    </style>
</head>

<body>
    <?php include "menu.php"; ?>
    <div class="container">
        <div class="text-center">
            <div class="display-1 fw-bold">Font Selections</div>
        </div>
        <div class="p-3 m-3 border rounded shadow">
            <div class="text-start" style="height:25vh; overflow-y:scroll; overflow-x:none">
                <?php echo implode($examples); ?>
            </div>
            <form method="post" enctype="multipart/form-data" class="text-center border-top">
                <!-- Hidden file input -->
                <input type="file" name="uploadedFile" id="fileInput" accept=".ttf" class="d-none">
                <!-- SVG Button to trigger file input -->
                <div><?php echo implode($error); ?></div>
                <div class="mt-3 d-flex justify-content-between border shadow p-3 rounded">
                    <label for="fileInput" class="btn btn-sm btn-primary" id="uploadBtn">
                        Select Font File
                    </label>
                    <input type="submit" class="btn btn-sm btn-primary" value="Upload">
                </div>
                <input type="hidden" name="pluginpage" value="font">
            </form>
        </div>
        <form>
            <div class="p-3 m-3 border rounded shadow">
                <div class="display-4 border-bottom mb-3">Navigation font</div>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <nav>
                                    <ul class="navbar-nav">
                                        <li class="nav-item">Home</li>
                                        <li class="nav-item">About</li>
                                        <li class="nav-item">Services</li>
                                        <li class="nav-item">Contact</li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col">
                                <nav>
                                    <ul>
                                        <li>Home</li>
                                        <li>About</li>
                                        <li>Services</li>
                                        <li>Contact</li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <label class="form-label fw-bold">Select Font:</label>
                        <input class="form-control" type="text" list="installedFonts" name="navigation" id="navigation"
                            value="<?php echo $_GET['navigation'] ?? ""; ?>">
                    </div>
                </div>
            </div>

            <div class="p-3 m-3 border rounded shadow">
                <div class="display-4 border-bottom mb-3">Headings font</div>
                <div class="row">
                    <div class="col">
                        <h1>Heading: Lorem ipsum dolor</h1>
                        <h2>Heading: Lorem ipsum dolor</h2>
                        <h3>Heading: Lorem ipsum dolor</h3>
                        <h4>Heading: Lorem ipsum dolor</h4>
                    </div>
                    <div class="col">
                        <label class="form-label fw-bold">Select Font:</label>
                        <input class="form-control" type="text" list="installedFonts" name="headings" id="headings"
                            value="<?php echo $_GET['headings'] ?? ""; ?>">
                    </div>
                </div>
            </div>
            <div class="p-3 m-3 border rounded shadow">
                <div class="display-4 border-bottom mb-3">Paragraph font</div>
                <div class="row">
                    <div class="col">
                        <p style="text-align: justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
                            eiusmod tempor
                            incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                            voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p>
                    </div>
                    <div class="col">
                        <label class="form-label fw-bold">Select Font:</label>
                        <input class="form-control" type="text" list="installedFonts" name="paragraphs" id="paragraphs"
                            value="<?php echo $_GET['paragraphs'] ?? ""; ?>">
                    </div>
                </div>
            </div>



            <datalist id="installedFonts">
                <?php echo implode($fonts); ?>
            </datalist>
            <input type="submit" class="btn btn-primary">
            <input type="hidden" name="pluginpage" value="font">
        </form>
    </div>
    <script>
        $(document).on("click", ".usefont", function () {
            console.log("What");
            font = $(this).data("fontfamily");
            cssfont = $(this).data("cssfont");
            dd = $("<div>")
                .attr("id", "popup")
                .attr("style", "position:fixed; top:0px; left:0px; bottom:0px; right:0px;display:flex; justify-content: center; align-items: center;z-index:1000000")
                .addClass("bg-white bg-opacity-75")
                .append(
                    $("<div>")
                        .addClass("bg-white border rounded shadow")
                        .append(
                            $("<div>")
                                .attr("id", "fontNav")
                                .addClass("fw-bold m-3 " + cssfont)
                                .html(font)
                                .data("fontfamily", font)
                        )
                        .append(
                            $("<div>")
                                .attr("id", "fontNav")
                                .addClass("fw-bold m-3")
                                .html("Use font in:")
                                .data("fontfamily", font)
                        )
                        .append(
                            $("<div>")
                                .attr("id", "fontNav")
                                .addClass("btn btn-primary m-3")
                                .html("Navigation")
                                .data("fontfamily", font)
                        )
                        .append(
                            $("<div>")
                                .attr("id", "fontHea")
                                .addClass("btn btn-primary m-3")
                                .html("Headings")
                                .data("fontfamily", font)
                        )
                        .append(
                            $("<div>")
                                .attr("id", "fontPar")
                                .addClass("btn btn-primary m-3")
                                .html("Paragraphs")
                                .data("fontfamily", font)
                        )
                        .append(
                            $("<div>")
                                .addClass("text-center")
                                .append(
                                    $("<div>")
                                        .attr("id", "cancel")
                                        .addClass("btn btn-primary m-3")
                                        .html("Cancel")
                                        .data("fontfamily", font)
                                )

                        )
                );
            $("body").append(dd);

        });
        $(document).on("click", "#fontNav", function () {
            tmp = $(this).data("fontfamily");
            $("#navigation").val(tmp);
            $("#popup").remove();
        });
        $(document).on("click", "#fontHea", function () {
            tmp = $(this).data("fontfamily");
            $("#headings").val(tmp);
            $("#popup").remove();
        });
        $(document).on("click", "#fontPar", function () {
            tmp = $(this).data("fontfamily");
            $("#paragraphs").val(tmp);
            $("#popup").remove();
        });
        $(document).on("click", "#cancel", function () {
            $("#popup").remove();
        });

        const fileInput = document.getElementById('fileInput');

        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                $("#uploadBtn").html(fileInput.files[0].name + " selected");

            } else {
                $("#uploadBtn").html("Select Font File");

            }
        });
    </script>
</body>

</html>