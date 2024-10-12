<?php
$menu['footer'] = [
    "sequence"=>7,
    "link" => "?pluginpage=footer",
    "title" => '<i class="display-5 bi bi-card-text"></i><div>Footer</div>',
    "description" => "Create and design the Footer of your site",
    "page"=>"footerPage",
];

function footerPage() {
    include __DIR__ . "/page.php";
}