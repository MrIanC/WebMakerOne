<?php
$menu['menu'] = [
    "sequence"=>6,
    "link" => "?pluginpage=menu",
    "title" => '<i class="display-5 bi bi-sign-turn-slight-right"></i><div>Navigation</div>',
    "description" => "Create your Navigation and create your menu items",
    "page"=>"menuPage"
    
];

function menuPage() {
    include __DIR__ . "/page.php";
}