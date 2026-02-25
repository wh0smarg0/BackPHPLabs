<?php
require_once("db.php");

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$allowed = ['title', 'name', 'topic'];
if (!in_array($sort, $allowed)) {
    $sort = 'articles.id';
}

$query = "SELECT articles.*, authors.name FROM articles 
          JOIN authors ON articles.author_id = authors.id 
          ORDER BY $sort ASC";

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
        <h4>Варіант 9 — Лабораторна робота №3</h4>
        <p>Виконала: Сахно Маргарита | Група: ІО-35</p>
        
        <div class="nav-buttons">
            <a href="newnote.php" class="btn-add"> Додати нову статтю</a>
            <a href="search.php" class="btn-nav btn-search"> Пошук по сайту</a>
            <a href="statistics.php" class="btn-nav btn-stats"> Статистика</a>
        </div>
    </header>

    <div style="margin-top: 20px; margin-bottom: 20px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <strong>Сортувати за:</strong>
        <a href="index.php?sort=title" style="margin-left: 10px; color: #3498db; text-decoration: none;">Заголовком</a> | 
        <a href="index.php?sort=name" style="margin-left: 10px; color: #3498db; text-decoration: none;">Автором</a> | 
        <a href="index.php?sort=topic" style="margin-left: 10px; color: #3498db; text-decoration: none;">Темою</a>
    </div>

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
                    <a href="details.php?id=<?php echo $row['id']; ?>"> 
                        <?php echo $row['title']; ?>
                    </a>
                </h2>
                <p><?php echo mb_strimwidth($row['content'], 0, 150, "..."); ?></p>
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <small><a href="details.php?id=<?php echo $row['id']; ?>">Читати повністю →</a></small>
                    
                    <div class="admin-actions" style="display: flex; gap: 15px;">
                        <a href="editnote.php?id=<?php echo $row['id']; ?>" class="btn-edit" style="color: #3498db; text-decoration: none;">✎ Редагувати</a>
                        <a href="deletenote.php?id=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           style="color: #e74c3c; text-decoration: none;"
                           onclick="return confirm('Ви впевнені, що хочете видалити цю статтю?')">
                           🗑 Видалити
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p style='text-align:center;'>Статей поки що немає.</p>";
    }
    ?>
</div>

</body>

</html>
