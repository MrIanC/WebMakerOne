<?php
$menu['edit'] = [
    "sequence"=>100,
    "link" => "?pluginpage=edit",
    "title" => '<b class="display-5 fw-bold">E</b><div>Edit Mode</div>',
    "description" => "Edits are live",
    "page"=>"editPage"
    
];

function editPage() {
    $webroot = $_SERVER['DOCUMENT_ROOT'];
    include __DIR__ . "/page.php";
}