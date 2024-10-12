<?php
$menu['users'] = [
    "link" => "?pluginpage=users",
    "title" => '<i class="display-5 bi bi-person-circle"></i><div>Users</div>',
    "description" => "Add, Edit, Delete users",
    "page" => "usersPage"
];

function usersPage()
{
    $usercredentialspath = realpath("../setup/plugins/users");
    include __DIR__ . "/page.php";
}