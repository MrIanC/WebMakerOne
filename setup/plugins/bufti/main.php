<?php
$menu['bufti'] = [
    "link" => "?pluginpage=bufti",
    "title" => '<i class="display-5 bi bi-download"></i><div>Update</div>',
    "description" => "Download an Update for another site from this install",
    "page" => "buftiPage"
];

function buftiPage()
{
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}

