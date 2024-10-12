<?php
session_start();

if (file_exists('path.php')) {
    include 'path.php';
    $users = glob("$usersPath/*.php");
    if (count($users) == 0) {
        include "install.php";
    } else {
        include "loginpage.php";   
    }
} else {
    include "install.php";
}

