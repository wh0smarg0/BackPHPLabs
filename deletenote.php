<?php
require_once("db.php");

// Перевіряємо, чи передано ID для видалення
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);

    // SQL запит на видалення
    $query = "DELETE FROM articles WHERE id = $id";

    if (mysqli_query($link, $query)) {
        // Після успішного видалення повертаємося на головну
        header("Location: index.php");
        exit();
    } else {
        echo "Помилка при видаленні: " . mysqli_error($link);
    }
} else {
    echo "ID не вказано.";
}
?>