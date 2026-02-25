<?php
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($link, $_POST['id']);
    $author_id = mysqli_real_escape_string($link, $_POST['author_id']);
    $topic = mysqli_real_escape_string($link, $_POST['topic']);
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $content = mysqli_real_escape_string($link, $_POST['content']);
    $illustrations = mysqli_real_escape_string($link, $_POST['illustrations']);

    $query = "UPDATE articles SET 
                author_id = '$author_id', 
                topic = '$topic', 
                title = '$title', 
                content = '$content', 
                illustrations = '$illustrations' 
              WHERE id = $id";

    if (mysqli_query($link, $query)) {
        header("Location: details.php?id=$id"); // Повертаємося до перегляду зміненої статті
        exit();
    } else {
        echo "Помилка оновлення: " . mysqli_error($link);
    }
}
?>