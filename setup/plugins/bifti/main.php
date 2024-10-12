<?php
$menu['bifti'] = [
    "link" => "?pluginpage=bifti",
    "title" => '<i class="display-5 bi bi-file-earmark-arrow-down-fill"></i><div>Install</div>',
    "description" => "Download Installation for new site",
    "page" => "biftiPage"
];

function biftiPage()
{
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}

