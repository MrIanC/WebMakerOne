<?php
$menu['palette'] = [
    "sequence"=>3,
    "link" => "?pluginpage=palette",
    "title" => '<i class="display-5 bi bi-palette"></i><div>Palette</div>',
    "description" => "Set custom colours for your Website",
    "page"=>"palettePage",
];

function palettePage() {
    include __DIR__ . "/page.php";
}