<?php
require_once("db.php");

// 1. Отримуємо статтю, яку хочемо змінити
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);
    $query = "SELECT * FROM articles WHERE id = $id";
    $res = mysqli_query($link, $query);
    $data = mysqli_fetch_array($res);

    if (!$data) {
        die("Статтю не знайдено.");
    }
}

// 2. Отримуємо список авторів для випадаючого списку
$authors_res = mysqli_query($link, "SELECT id, name FROM authors");
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати статтю</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← Скасувати та повернутися</a>
    
    <div class="details-box">
        <h1>Редагування статті</h1>
        
        <form action="updatenote.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

            <label>Автор:</label><br>
            <select name="author_id" required style="width: 100%; padding: 10px; margin-bottom: 20px;">
                <?php while($author = mysqli_fetch_array($authors_res)): ?>
                    <option value="<?php echo $author['id']; ?>" <?php if($author['id'] == $data['author_id']) echo 'selected'; ?>>
                        <?php echo $author['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Тема:</label><br>
            <input type="text" name="topic" value="<?php echo $data['topic']; ?>" required style="width: 100%; padding: 10px; margin-bottom: 20px;">

            <label>Заголовок:</label><br>
            <input type="text" name="title" value="<?php echo $data['title']; ?>" required style="width: 100%; padding: 10px; margin-bottom: 20px;">

            <label>Текст статті:</label><br>
            <textarea name="content" rows="10" required style="width: 100%; padding: 10px; margin-bottom: 20px;"><?php echo $data['content']; ?></textarea>

            <label>Ілюстрація:</label><br>
            <input type="text" name="illustrations" value="<?php echo $data['illustrations']; ?>" style="width: 100%; padding: 10px; margin-bottom: 20px;">

            <button type="submit" style="background: #3498db; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer;">
                Оновити дані
            </button>
        </form>
    </div>
</div>

</body>
</html>