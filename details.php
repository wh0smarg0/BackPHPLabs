<?php
require_once("db.php");

// Отримуємо ID із адресного рядка
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);
    
    // Запит на отримання повної інформації про статтю та автора
    $query = "SELECT articles.*, authors.name, authors.address 
              FROM articles 
              JOIN authors ON articles.author_id = authors.id 
              WHERE articles.id = $id";
              
    $res = mysqli_query($link, $query);
    $data = mysqli_fetch_array($res);
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?php echo $data ? $data['title'] : 'Стаття не знайдена'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← Назад до списку статей</a>

    <?php if ($data): ?>
        <header>
            <span class="topic-badge"><?php echo $data['topic']; ?></span>
            <h1><?php echo $data['title']; ?></h1>
        </header>

        <div class="meta-info">
            <p><b>Автор:</b> <?php echo $data['name']; ?></p>
            <p><b>Адреса автора:</b> <?php echo $data['address']; ?></p>
        </div>

        <div class="content">
            <?php echo $data['content']; ?>
        </div>

        <?php if (!empty($data['illustrations'])): ?>
            <div class="illustration-box">
                <h3>Ілюстрація до матеріалу</h3>
                <img src="img/<?php echo $data['illustrations']; ?>" alt="Ілюстрація">
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <h2>Запис не знайдено</h2>
            <p>Можливо, стаття була видалена або посилання некоректне.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>