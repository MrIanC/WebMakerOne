<?php
$menu['ai'] = [
    
    "link" => "?pluginpage=ai",
    "title" => '<span class="display-5">AI</span><div>AI Api</div>',
    "description" => "Create and design the Footer of your site",    
    "page" => "aiPage"
];
function aiPage()
{
    include __DIR__ . "/api.php";
}