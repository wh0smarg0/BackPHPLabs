<?php
require_once("db.php");

// 1. Загальна кількість записів у таблицях [cite: 5, 6, 60]
$res_authors = mysqli_query($link, "SELECT COUNT(id) AS total FROM authors");
$count_authors = mysqli_fetch_assoc($res_authors)['total'];

$res_articles = mysqli_query($link, "SELECT COUNT(id) AS total FROM articles");
$count_articles = mysqli_fetch_assoc($res_articles)['total'];

// 2. Підрахунок кількості заміток за останній місяць [cite: 7, 65, 66]
$date_array = getdate(); // [cite: 74]
$begin_date = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'], 1, $date_array['year'])); // [cite: 78]
$end_date = date("Y-m-d", mktime(0, 0, 0, $date_array['mon'] + 1, 0, $date_array['year'])); // [cite: 84]

$query_lm = "SELECT COUNT(id) AS lmnotes FROM articles WHERE created >= '$begin_date' AND created <= '$end_date'"; // [cite: 91]
$res_lm = mysqli_query($link, $query_lm);
$lmnotes_num = mysqli_fetch_assoc($res_lm)['lmnotes'];

// 3. Остання додана замітка (LIMIT) [cite: 8, 98, 99]
$res_last = mysqli_query($link, "SELECT id, title FROM articles ORDER BY id DESC LIMIT 1");
$row_last = mysqli_fetch_assoc($res_last);

// 4. Автор з найбільшою кількістю статей (GROUP BY) [cite: 9, 105, 117]
$query_top = "SELECT authors.name, COUNT(articles.id) as art_count 
              FROM articles, authors 
              WHERE articles.author_id = authors.id 
              GROUP BY authors.id 
              ORDER BY COUNT(articles.id) DESC LIMIT 0,1"; // [cite: 118, 120]
$res_top = mysqli_query($link, $query_top);
$top_row = mysqli_fetch_assoc($res_top);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Статистика сайту</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Сторінка статистики</h1> <div class="article-card">
        <p>Зроблено записів (авторів) — <b><?php echo $count_authors; ?></b></p> <p>Опубліковано статей — <b><?php echo $count_articles; ?></b></p>
        <p>За останній місяць створено статей — <b><?php echo $lmnotes_num; ?></b></p> <p>Мій останній запис — <b><?php echo $row_last['id'] . " " . $row_last['title']; ?></b></p> <p>Найбільш продуктивний автор — <b><?php echo $top_row['name']; ?></b></p> </div>
    
    <p><a href="index.php">Повернутися на головну сторінку</a></p> </div>
</body>
</html>