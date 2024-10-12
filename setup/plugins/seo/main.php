<?php
$menu['seo'] = [
    "sequence"=>9,
    "link" => "?pluginpage=seo",
    "title" => '<i class="display-5 bi bi-search-heart"></i><div>SEO</div>',
    "description" => "This Feature not yet completed.",
    "page"=>"seoPage"
];

function seoPage() {
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}