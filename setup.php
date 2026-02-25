<?php

$link = mysqli_connect("localhost", "root", "", "", 3307); 

if (!$link) {
    die("Немає з'єднання з сервером: " . mysqli_connect_error()); // 
}

// Створення БД
$db = "LAB1";
$query = "CREATE DATABASE IF NOT EXISTS $db";
if (mysqli_query($link, $query)) {
    echo "База даних $db успішно створена<br>";
}

// Створення користувача admin
$query_user = "GRANT ALL PRIVILEGES ON $db.* TO 'admin'@'localhost' IDENTIFIED BY 'admin'";
if (mysqli_query($link, $query_user)) {
    echo "Користувач 'admin' створений успішно";
}
?>