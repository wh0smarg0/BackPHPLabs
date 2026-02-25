<?php
$host = "localhost";
$user = "admin";
$pass = "admin";
$db = "LAB1";
$port = 3307; 

$link = mysqli_connect($host, $user, $pass, $db, $port);

if (!$link) {
    die("Помилка підключення: " . mysqli_connect_error());
}
?>