<?php
$menu['content'] = [
    "sequence"=>60,
    "link" => "?pluginpage=content",
    "title" => '<i class="display-5 bi bi-body-text"></i><div>Content</div>',
    "description" => "Create your content pages for your website",
    "page" => "contentPage"
];

function contentPage()
{
    $webroot = realpath("../");
    include __DIR__ . "/page.php";
}