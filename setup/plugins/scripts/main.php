<?php
$menu['scripts'] = [
    "sequence"=>90,
    "link" => "?pluginpage=scripts",
    "title" => '<i class="display-5">S</i><div>Scripts</div>',
    "description" => "Scripts to load on every page",
    "page"=>"scriptsPage",
];
$menu['scriptsEdit'] = [
    "link" => "?pluginpage=scriptsEdit",
    "page"=>"editScriptsPage",
];

function scriptsPage() {
    include __DIR__ . "/page.php";
}function editScriptsPage() {
    include __DIR__ . "/jsedit.php";
}