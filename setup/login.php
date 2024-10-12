<?php
session_start();
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

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

