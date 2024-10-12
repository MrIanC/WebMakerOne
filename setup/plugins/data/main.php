<?php
$menu['data'] = [
    "sequence"=>5,
    "link" => "?pluginpage=data",
    "title" => '<i class="display-5 bi bi-bar-chart-steps"></i><div>Data</div>',
    "description" => "Structured data for your Company or Business",
    "page" => "dataPage",
    
];

function dataPage()
{
    include __DIR__ . "/page.php";
}