<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

$addrem = [];
foreach ($_POST as $key => $value) {
    $addrem[] = $key;
}


libxml_use_internal_errors(true);
$online = new DOMDocument();
$built = new DOMDocument();
$online->loadHTMLFile($_SERVER['DOCUMENT_ROOT'] . "/setup/index/onlinebase.html");
$built->loadHTMLFile($_SERVER['DOCUMENT_ROOT'] . "/setup/index/templatebase.html");

libxml_clear_errors();
$onlinescripts = $online->getElementsByTagName('script');
$templatescripts = $built->getElementsByTagName('script');

$scriptSrcs = [];

foreach ($onlinescripts as $script) {
    $src = $script->getAttribute('src');
    if ($src) {
        if (str_contains($src, "/resources/js/page")) {
            $script->parentNode->removeChild($script);
            $scriptSrcs[] = pathinfo($src, PATHINFO_FILENAME);
        }
    }
}

unset($script);

foreach ($templatescripts as $script) {
    $src = $script->getAttribute('src');
    if ($src) {
        if (str_contains($src, "/resources/js/page")) {
            $script->parentNode->removeChild($script);
            $scriptSrcs[] = pathinfo($src, PATHINFO_FILENAME);
        }
    }
}
unset($script);


$available = [];
foreach (glob(pattern: __DIR__ . "/available/*.js") as $filename) {
    $tmp = pathinfo($filename, PATHINFO_FILENAME);
    $available[] = $tmp;
    if (in_array($tmp, $addrem)) {
        if (!empty($_POST)) {
            copy(__DIR__ . "/available/$tmp.js", $_SERVER['DOCUMENT_ROOT'] . "/resources/js/page/$tmp.js");

            $onlinenewScript = $online->createElement('script'); // Create a new script element
            $onlinenewScript->setAttribute('src', "/resources/js/page/$tmp.js"); // Set the src attribute
            $onlinehead = $online->getElementsByTagName('head')->item(0); // Get the <head> tag

            if ($onlinehead) {
                $onlinehead->appendChild($onlinenewScript); // Append the new script to the head
            }
            
            $onlinenewScript = $built->createElement('script'); // Create a new script element
            $onlinenewScript->setAttribute('src', "/resources/js/page/$tmp.js"); // Set the src attribute
            $templatehead = $built->getElementsByTagName('head')->item(0); // Get the <head> tag
            
            if ($templatehead) {
                $templatehead->appendChild($onlinenewScript); // Append the new script to the head
            }

        }
    } else {
        if (!empty($_POST)) {
            $fn = $_SERVER['DOCUMENT_ROOT'] . "/resources/js/page/$tmp.js";

            if (file_exists($fn)) {
                unlink($fn);
            }
        }

    }
}

$online->saveHTMLFile($_SERVER['DOCUMENT_ROOT'] . "/setup/index/online.html");
$built->saveHTMLFile($_SERVER['DOCUMENT_ROOT'] . "/setup/index/template.html");


$installed = [];
foreach (glob($_SERVER['DOCUMENT_ROOT'] . "/resources/js/page/*.js") as $filename) {
    $installed[] = pathinfo($filename, PATHINFO_FILENAME);
}
$allscripts = [];
foreach ($available as $key => $value) {
    $checked = "";
    if (in_array($value, $installed)) {
        $checked = "checked";
    }

    $allscripts[] = "<div class=\"d-flex align-items-center\">
    <div class=\"p-3 m-0\">
        <input type=\"checkbox\" name=\"$value\" $checked>
    </div>
    <div class=\"p-3  m-0\">
        <label for=\"$value\">$value</label>
    </div>
    </div>";

}

//print_r($_POST);


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
            <div class="display-1 fw-bold">Scripts</div>
        </div>
        <div>
            <div class="h1">Active Scripts</div>
            <form method="POST">
                <?php echo implode($allscripts); ?>

                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <script>
    </script>
</body>

</html>