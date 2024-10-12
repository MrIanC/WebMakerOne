<?php
$menu['actions'] = [
    "sequence"=>12,
    "link" => "?pluginpage=actions",
    "title" => '<b class="display-5 fw-bold">A</b><div>actions</div>',
    "description" => "JQuery or JS onclick Action files",
    "page"=>"actionsPage"
    
];

function actionsPage() {
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}