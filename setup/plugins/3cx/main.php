<?php
$menu['cxchat'] = [
    
    "link" => "?pluginpage=cxchat",
    "title" => '<span class="display-5">3cx</span><div>3CX Details</div>',
    "description" => "3CX chat credentials",    
    "page" => "cxchatPage"
];
function cxchatPage()
{
    include __DIR__ . "/page.php";
}