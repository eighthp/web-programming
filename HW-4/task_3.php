<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подсчёт слов и символов</title>
    <style>
        textarea { width: 300px; height: 100px; }
        button { margin-top: 10px; padding: 5px 15px; }
    </style>
</head>
<body>
    <form method="post">
        <textarea name="text" placeholder="Введите текст"><?php 
            echo isset($_POST['text']) ? htmlspecialchars($_POST['text']) : ''; 
        ?></textarea><br>
        <button type="submit">Подсчитать</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
        $text = $_POST['text'];
       
        $charCount = mb_strlen($text);
        
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = count($words);
        
        echo "<p>Количество символов: $charCount</p>";
        echo "<p>Количество слов: $wordCount</p>";
    }
    ?>
</body>
</html>