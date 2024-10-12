<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

if (isset($_GET['title']) && isset($_GET['link'])) {
    $navigation = json_decode(file_get_contents("../resources/json/navigation.json"), true);

    $title = $_GET['title'];
    $link = $_GET['link'];
    $navigation['links'][$title] = $link;
    file_put_contents("../resources/json/navigation.json", json_encode($navigation, JSON_PRETTY_PRINT));

    if (is_dir("..$link")) {
        file_put_contents("..$link/index.html", file_get_contents("../index.html"));
    } else {
        mkdir("..$link");
        file_put_contents("..$link/index.html", file_get_contents("../index.html"));
    }
}


if (isset($_GET['delete'])) {
    $navigation = json_decode(file_get_contents("../resources/json/navigation.json"), true);
    $title = $_GET['delete'];
    unset($navigation['links'][$title]);
    file_put_contents("../resources/json/navigation.json", json_encode($navigation, JSON_PRETTY_PRINT));
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$current_url = $protocol . $_SERVER['HTTP_HOST'];

$navigation = json_decode(file_get_contents("../resources/json/navigation.json"), true);
$sitemap[] = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$sitemap[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$robots[] = 'User-agent: *' . "\n";
$robots[] = 'Disallow: /' . "\n";

foreach ($navigation['links'] as $key => $value) {
    $sitemap[] = '  <url>' . "\n";
    $sitemap[] = '      <loc>' . $current_url . $value . '</loc>' . "\n";
    $sitemap[] = '      <lastmod>' . date("Y-m-d") . '</lastmod>' . "\n";
    $sitemap[] = '      <priority>0.5</priority>' . "\n";
    $sitemap[] = '      <changefreq>yearly</changefreq>' . "\n";
    $sitemap[] = '  </url>' . "\n";

    if ($value == "/") {
        $robots[] = 'Allow: ' . $value . '$' . "\n";
    } else {
        $robots[] = 'Allow: ' . $value . '/$' . "\n";
    }


}
$sitemap[] = '</urlset>' . "\n";

$robots[] = 'Sitemap: ' . $current_url . '/sitemap.xml' . "\n";

file_put_contents("../sitemap.xml", implode($sitemap));
file_put_contents("../robots.txt", implode($robots));


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
            <div class="display-1 fw-bold">Build Menu</div>
        </div>
        <div id="items" class="row">

            <div class="col">
                <form>
                    <div>
                        <div class="fw-bold mb-3">Create New Navigation Item</div>
                        <label class="form-label">Title Text</label>
                        <input class="form-control" type="text" name="title">
                        <label class="form-label">Page Link</label>
                        <input class="form-control" type="text" list="pages" name="link">
                        <button class="btn btn-primary mt-3">Create</button>
                    </div>
                    <input type="hidden" name="pluginpage" value="menu">
                </form>
            </div>
            <div class="col">
                <div class="fw-bold mb-3">Delete Menu Items</div>
                <form>
                    <?php

                    $navigation = json_decode(file_get_contents("../resources/json/navigation.json"), true);

                    foreach ($navigation['links'] as $key => $value) {

                        echo "<div class=\"mb-3 d-flex justify-content-between\"><span>Title: $key - Link: $value</span><button class=\"btn btn-danger\" name=\"delete\" value=\"$key\">X</button></div>";

                    }

                    ?>
                    <input type="hidden" name="pluginpage" value="menu">
                </form>
            </div>

        </div>
        <datalist id="pages">
            <?php
            foreach (glob("../resources/parts/pages/*.html") as $page) {
                $link = "/" . str_replace(".html", "", basename($page));
                echo "<option value=\"$link\">$link</option>";
            }
            echo ""
                ?>
        </datalist>
    </div>
    <div class="text-end container">
        <a href="?pluginpage=grapesjs&pagename=navigation">Open in Editor</a>

    </div>
    <script>
    </script>
</body>

</html>