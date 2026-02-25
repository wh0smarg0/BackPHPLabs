<?php
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримуємо дані та захищаємо від SQL-ін'єкцій
    $author_id = mysqli_real_escape_string($link, $_POST['author_id']);
    $topic = mysqli_real_escape_string($link, $_POST['topic']);
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $content = mysqli_real_escape_string($link, $_POST['content']);
    $illustrations = mysqli_real_escape_string($link, $_POST['illustrations']);

    // SQL запит на додавання (INSERT)
    $query = "INSERT INTO articles (author_id, topic, title, content, illustrations) 
              VALUES ('$author_id', '$topic', '$title', '$content', '$illustrations')";

    if (mysqli_query($link, $query)) {
        // Якщо успішно — перенаправляємо на головну
        header("Location: index.php");
        exit();
    } else {
        echo "Помилка: " . mysqli_error($link);
    }
}
?>