<?php
session_start();
$username = $_SESSION['user_logged_in'];
include 'path.php';
if (!file_exists("$usersPath/$username.php")) {
    header("Location: login.php");
    exit();
}