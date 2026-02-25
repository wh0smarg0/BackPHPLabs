<?php
require_once("db.php");

// Запит на вибірку статей та імен авторів
$query = "SELECT articles.*, authors.name FROM articles 
          JOIN authors ON articles.author_id = authors.id";

$result = mysqli_query($link, $query);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список статей</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">
    <header>
        <h1>Статті та автори</h1>
	<h4>Варіант 9 — Лабораторна робота №1</h4>
        <p>Виконала: Сахно Маргарита | Група: ІО-35</p>
    </header>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            ?>
            <div class="article-card">
                <div class="meta">
                    <span class="topic-badge"><?php echo $row['topic']; ?></span> | 
                    Автор: <b><?php echo $row['name']; ?></b>
                </div>
                <h2>
                    <a href="details.php?id=<?php echo $row['id']; ?>"> <?php echo $row['title']; ?>
                    </a>
                </h2>
                <p><?php echo mb_strimwidth($row['content'], 0, 150, "..."); ?></p>
                <small><a href="details.php?id=<?php echo $row['id']; ?>">Читати повністю →</a></small>
            </div>
            <?php
        }
    } else {
        echo "<p>Статей поки що немає.</p>";
    }
    ?>
</div>

</body>
</html>