<?php 

$menu['grapesjs'] = [
    "page"=>"grapesjsPage"
];
function grapesjsPage() {
    $realpath = $_SERVER['DOCUMENT_ROOT'];
    $thisDirJS = str_replace(realpath($realpath),"",__DIR__);;
    include __DIR__ . "/page.php";
}