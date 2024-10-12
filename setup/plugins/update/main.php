<?php
$menu['update'] = [
    "link" => "?pluginpage=update",
    "title" => '<i class="display-5 bi bi-upload"></i><div>Update</div>',
    "description" => "Update this site from a zip file",
    "page" => "updatePage"
];

function updatePage()
{
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}

