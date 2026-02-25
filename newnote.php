<?php
require_once("db.php");

// Отримуємо список авторів для випадаючого списку
$authors_query = "SELECT id, name FROM authors";
$authors_result = mysqli_query($link, $authors_query);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати нову статтю</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← Назад до списку</a>
    
    <div class="details-box">
        <h1>Створення нової статті</h1>
        
        <form action="addnote.php" method="POST">
            <label>Виберіть автора:</label><br>
            <select name="author_id" required style="width: 100%; padding: 10px; margin-bottom: 20px;">
                <?php while($author = mysqli_fetch_array($authors_result)): ?>
                    <option value="<?php echo $author['id']; ?>">
                        <?php echo $author['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Тема статті:</label><br>
            <input type="text" name="topic" required style="width: 100%; padding: 10px; margin-bottom: 20px;">

            <label>Заголовок:</label><br>
            <input type="text" name="title" required style="width: 100%; padding: 10px; margin-bottom: 20px;">

            <label>Текст статті:</label><br>
            <textarea name="content" rows="10" required style="width: 100%; padding: 10px; margin-bottom: 20px;"></textarea>

            <label>Назва файлу ілюстрації (наприклад, photo.jpg):</label><br>
            <input type="text" name="illustrations" style="width: 100%; padding: 10px; margin-bottom: 20px;">

            <button type="submit" style="background: #27ae60; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Зберегти статтю
            </button>
        </form>
    </div>
</div>

</body>
</html>