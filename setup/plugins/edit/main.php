<?php
$menu['edit'] = [
    "sequence"=>10,
    "link" => "?pluginpage=edit",
    "title" => '<b class="display-5 fw-bold">E</b><div>Edit Mode</div>',
    "description" => "Edits are live",
    "page"=>"editPage"
    
];

function editPage() {
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}