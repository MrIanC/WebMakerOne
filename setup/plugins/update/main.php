<?php
$menu['update'] = [
    "link" => "?pluginpage=update",
    "title" => '<i class="display-5 bi bi-upload"></i><div>Update</div>',
    "description" => "Update this site from a zip file",
    "page" => "updatePage"
];

function updatePage()
{
    $webroot = $_SERVER['DOCUMENT_ROOT'];
    include __DIR__ . "/page.php";
}


$menu['updateFromGit'] = [
    "page" => "updateFromGitPage"
];

function updateFromGitPage()
{
    $webroot = $_SERVER['DOCUMENT_ROOT'];
    include __DIR__ . "/updatefromgit.php";
}

