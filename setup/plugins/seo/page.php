<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

$headingsTest = [];
$imagesTest = [];

$headingArray = [];
$imagesArray = [];

$jsonldTest = [];

$faviconTest = (file_exists("../uploads/favicon.ico")?"Favicon Icon Exists":"Upload a favicon Icon to pass this test");

foreach (glob("../resources/parts/pages/*.html") as $key => $page) {
    $pagename = str_replace(".html", "", basename($page));
    $checking = '<!DOCTYPE html><html>' . file_get_contents($page) . '</html>';
    libxml_use_internal_errors(true);

    $doc = new DOMDocument();
    $doc->loadHTML($checking);
    libxml_clear_errors();
    $xpath = new DOMXPath($doc);

    // Query for all <h1> to <h6> tags
    $headings = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');
    $images = $doc->getElementsByTagName('img');


    // Iterate over the results and add them to the array
    foreach ($headings as $heading) {
        $headingArray[$pagename][] = [
            'tag' => $heading->nodeName, // Get the tag name (e.g., h1, h2)
            'text' => $heading->nodeValue // Get the text content inside the tag
        ]; // Or $heading->textContent to get the text inside the tag
    }

    foreach ($images as $image) {
        $imagesArray[$pagename][] = [
            'src' => $image->getAttribute("src"),
            'alt' => $image->getAttribute("alt")
        ];
    }
}

foreach ($headingArray as $pagename => $page) {
    $init = 0;
    foreach ($page as $key => $value) {
        $hval = (int) str_replace("h", "", $value['tag']);

        if (abs($hval - $init) > 1)
            $headingsTest[] = "<div>$pagename : {$value['text']} - Headings are not in Order</div>";
        $init = $hval;
    }
}

foreach ($imagesArray as $pagename => $page) {
    foreach ($page as $key => $value) {
        $warning = "";
        if ($value['alt'] == "") {
            $warning = "<span class=\"text-danger fw-bold\">NO ALT Attribute</span>";
            $imagecheck = "open";
        }
        $imagesTest[] = "<div><div class=\"d-flex justify-content-between\"><div>$pagename</div><img style=\"height:32px\" src=\"{$value['src']}\" > <div>{$value['alt']}</div>$warning</div></div>";
    }
}


if (empty($headingsTest)) {
    $headingsTest[] = "<div>Headings Order is Good</div>";
}



$file = $webroot . '/resources/json/business.json';
$jsonldContent = file_get_contents($file);

// Step 2: Decode the JSON-LD
$jsonldData = json_decode($jsonldContent, true);

// Step 3: Check if the JSON was decoded correctly
if (json_last_error() === JSON_ERROR_NONE) {
    $jsonldTest[] = "JSON-LD is valid.\n";
} else {
    $jsonldTest[] = "JSON-LD is not valid. Error: " . json_last_error_msg() . "\n";
}
$imagecheck = "";



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
    <script id="jsonld" type="application/ld+json"></script>
</head>

<body>
    <?php include "menu.php"; ?>
    <h1>SEO Tools</h1>
    <h2>Structured Data</h2>
    <div><?php echo implode($jsonldTest); ?></div>
    <h2>Headings</h2>
    <div><?php echo implode($headingsTest); ?></div>
    <h2>Images</h2>
    <details <?php echo $imagecheck ?>>
        <summary>Site Images</summary>
        <div><?php echo implode($imagesTest); ?></div>
    </details>
    <h2>Site Icon</h2>
    <div>
    <div><?php echo $faviconTest; ?></div>
    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>