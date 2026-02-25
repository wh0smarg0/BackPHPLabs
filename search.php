<?php
require_once("db.php");
$results = null;

// 1. Пошук за ключовим словом або частиною тексту
if (isset($_GET['usersearch']) && !empty($_GET['usersearch'])) {
    $search = mysqli_real_escape_string($link, $_GET['usersearch']);
    
    $pattern = str_replace('*', '%', $search);
    
    if (strpos($search, '*') === false) {
        $pattern = "%" . $search . "%";
    }

    // Запит шукає і в заголовку, і в тексті статті
    $query = "SELECT * FROM articles WHERE title LIKE '$pattern' OR content LIKE '$pattern'";
    $results = mysqli_query($link, $query);
}

if (isset($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_from'])) {
    $from = mysqli_real_escape_string($link, $_GET['date_from']);
    $to = mysqli_real_escape_string($link, $_GET['date_to']);
    
    $query = "SELECT * FROM articles WHERE created BETWEEN '$from' AND '$to'";
    $results = mysqli_query($link, $query);
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пошук інформації</title> <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Сторінка пошуку інформації</h1>

    <div class="article-card">
        <form action="search.php" method="GET">
            <label>Рядок пошуку:</label><br>
            <input type="text" name="usersearch" value="title*">
            <input type="submit" value="Search">
        </form>
    </div>

    <div class="article-card">
        <form action="search.php" method="GET">
            <label>Діапазон дат (YYYY-MM-DD):</label><br>
            <input type="text" name="date_from" value="2026-02-01">
            <input type="text" name="date_to" value="2026-02-25">
            <input type="submit" value="Search">
        </form>
    </div>

    <?php if ($results): ?>
        <h3>Результати пошуку:</h3>
        <table border="1" width="100%"> <tr>
                <th>ID</th>
                <th>Created & Title</th>
                <th>Article content</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['created'] . " " . $row['title']; ?></td>
                    <td><?php echo $row['content']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

    <p><a href="index.php">Повернутися на головну сторінку сайту</a></p> </div>
</body>
</html>