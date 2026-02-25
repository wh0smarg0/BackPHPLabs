<?php
$link = mysqli_connect("localhost", "admin", "admin", "LAB1", 3307);

$query = "CREATE TABLE authors (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    login VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)";

if (mysqli_query($link, $query)) {
    echo "Таблиця authors створена успішно.";
}
?>