<?php
$menu['zip'] = [
    "link" => "?pluginpage=zip",
    "title" => '<i class="display-5 bi bi-cloud-download"></i><div>Website</div>',
    "description" => "Download HTML, CSS, JS website",
    "page" => "zipPage"
];

function zipPage()
{
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}