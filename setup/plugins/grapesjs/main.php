<?php 

$menu['grapesjs'] = [
    "page"=>"grapesjsPage"
];
function grapesjsPage() {
    $thisDirJS = str_replace(realpath("../"),"",__DIR__);;
    
    
    include __DIR__ . "/page.php";
}