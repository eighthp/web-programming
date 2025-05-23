<?php
// Конфигурация базы данных
define('DB_HOST', 'db');
define('DB_USER', 'root');
define('DB_PASS', 'helloworld');
define('DB_NAME', 'web');

// Установка соединения с базой данных
try {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Обработка формы добавления объявления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['submit_ad'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($email && $title && $category && $description) {
        $stmt = $db->prepare("INSERT INTO ads (email, title, category, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $title, $category, $description);
        $stmt->execute();
        $stmt->close();
        
        // Перенаправление для предотвращения повторной отправки формы
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Получение списка объявлений
$ads = [];
$result = $db->query("SELECT * FROM ads ORDER BY created_at DESC");
if ($result) {
    $ads = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доска объявлений</title>
    <style>
        body { font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background-color: #f8f9fa; color: #333; line-height: 1.6; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; }
        input, select, textarea { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; transition: border 0.3s ease; }
        input:focus, select:focus, textarea:focus { border-color: #3498db; outline: none; box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2); }
        textarea { min-height: 120px; resize: vertical; }
        button { background: linear-gradient(135deg, #3498db, #2c3e50); color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        button:hover { background: linear-gradient(135deg, #2980b9, #1a252f); transform: translateY(-2px); box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15); }
        .ad-card { background: white; border: 1px solid #e0e0e0; padding: 20px; margin-bottom: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .ad-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
        .ad-title { color: #2c3e50; margin-top: 0; margin-bottom: 10px; font-size: 22px; font-weight: 700; }
        .ad-meta { color: #7f8c8d; font-size: 0.9em; margin-bottom: 15px; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 30px; font-weight: 800; }
    </style>
</head>
<body>
    <h1>Доска объявлений</h1>
    
    <section class="ad-form">
        <h2>Новое объявление</h2>
        <form method="post">
            <div class="form-group">
                <label for="email">Ваш Контактный Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="category">Категория:</label>
                <select id="category" name="category" required>
                    <option value="">-- Выберите категорию --</option>
                    <option value="Электроника">Электроника</option>
                    <option value="Недвижимость">Недвижимость</option>
                    <option value="Транспорт">Транспорт</option>
                    <option value="Работа">Работа</option>
                    <option value="Услуги">Услуги</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="title">Заголовок объявления:</label>
                <input type="text" id="title" name="title" required maxlength="100">
            </div>
            
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            
            <button type="submit" name="submit_ad">Опубликовать</button>
        </form>
    </section>
    
    <section class="ads-list">
        <h2>Последние объявления</h2>
        
        <?php if (empty($ads)): ?>
            <p>Объявлений пока нет. Будьте первым!</p>
        <?php else: ?>
            <?php foreach ($ads as $ad): ?>
                <article class="ad-card">
                    <h3 class="ad-title"><?= htmlspecialchars($ad['title']) ?></h3>
                    <div class="ad-description"><?= nl2br(htmlspecialchars($ad['description'])) ?></div>
                    <div class="ad-meta">
                        Категория: <strong><?= htmlspecialchars($ad['category']) ?></strong> | 
                        Контакты: <?= htmlspecialchars($ad['email']) ?> | 
                        Дата: <?= date('d.m.Y H:i', strtotime($ad['created_at'])) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</body>
</html>