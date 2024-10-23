<?php
$menu['media'] = [
    "sequence" => 0,
    "link" => "?pluginpage=media",
    "title" => '<i class="display-5 bi bi-image"></i><div>Media</div>',
    "description" => "Upload Pictures, Videos, Audio for your project",
    "page" => "mediaPage",
];
$menu['mediaedit'] = [
    "page" => "mediaEditPage",
];
function mediaEditPage()
{
    include __DIR__ . "/onefile.php";
}
function mediaPage()
{
    include __DIR__ . "/page.php";
}