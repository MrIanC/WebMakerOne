<?php
$menu['font'] = [
    "sequence"=>4,
    "link" => "?pluginpage=font",
    "title" => '<i class="display-5 bi bi-fonts"></i><div>Fonts</div>',
    "description" => "Set Your fonts for navigation, headings, and paragraphs",
    "page"=>"fontPage"

];

function fontPage() {
    include __DIR__ . "/page.php";
}