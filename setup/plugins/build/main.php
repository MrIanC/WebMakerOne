<?php
$menu['build'] = [
    "sequence"=>11,
    "link" => "?pluginpage=build",
    "title" => '<b class="display-5 fw-bold">B</b><div>Build</div>',
    "description" => "Built sites are more robust and work better in more browsers",
    "page"=>"buildPage"
    
];

function buildPage() {
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}