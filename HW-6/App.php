<?php
// Подключаем класс RestApiClient для работы с API
require_once 'RestApiClient.php';

/**
 * Демонстрационный скрипт для работы с JSONPlaceholder API
 * Показывает примеры GET, POST, PUT и DELETE запросов
 */

// Инициализация API клиента с базовым URL и тестовыми учетными данными
// (JSONPlaceholder на самом деле не требует аутентификации)
$apiClient = new RestApiClient(
    'https://jsonplaceholder.typicode.com', // Базовый URL API
    'test_user',                           // Тестовый логин (необязательно)
    'test_pass'                            // Тестовый пароль (необязательно)
);

// 1. Пример GET-запроса - получение списка комментариев (первые 5)
echo "=== Получение списка комментариев ===\n";
$comments = $apiClient->get('/comments?_limit=5'); // Параметр _limit=5 ограничивает выборку

// Обработка результата GET-запроса
if ($comments['success']) {
    echo "Последние 5 комментариев:\n";
    // Выводим каждый комментарий в удобочитаемом формате
    foreach ($comments['data'] as $comment) {
        echo "✉️ [{$comment['id']}] {$comment['name']}\n";  // ID и заголовок
        echo "   {$comment['body']}\n\n";                   // Тело комментария
    }
} else {
    // Вывод информации об ошибке, если запрос не удался
    echo "Ошибка при запросе: {$comments['status']}\n";
    if ($comments['error']) {
        echo "Детали: {$comments['error']}\n";
    }
}

// 2. Пример POST-запроса - создание нового комментария
echo "\n=== Создание нового комментария ===\n";
// Подготавливаем данные для нового комментария
$newComment = [
    'postId' => 1,                              // ID поста
    'name' => 'API Test Comment',               // Заголовок
    'email' => 'api.test@example.com',          // Email автора
    'body' => 'Этот комментарий создан через REST API клиент' // Текст
];

// Отправляем POST-запрос для создания комментария
$createResult = $apiClient->post('/comments', $newComment);

// Обработка результата создания
if ($createResult['success']) {
    echo "Комментарий успешно создан!\n";
    echo "ID нового комментария: {$createResult['data']['id']}\n";
    echo "Email автора: {$createResult['data']['email']}\n";
} else {
    echo "Ошибка при создании: {$createResult['status']}\n";
}

// 3. Пример PUT-запроса - обновление существующего комментария
echo "\n=== Обновление комментария (ID: 1) ===\n";
// Данные для обновления (только те поля, которые нужно изменить)
$updatedComment = [
    'name' => 'Updated Comment',               // Новый заголовок
    'body' => 'Этот комментарий был обновлен через API' // Новый текст
];

// Отправляем PUT-запрос для обновления комментария с ID=1
$updateResult = $apiClient->put('/comments/1', $updatedComment);

// Обработка результата обновления
if ($updateResult['success']) {
    echo "Комментарий успешно обновлен!\n";
    echo "Новое содержимое: {$updateResult['data']['body']}\n";
} else {
    echo "Ошибка при обновлении: {$updateResult['status']}\n";
}

// 4. Пример DELETE-запроса - удаление комментария
echo "\n=== Удаление комментария (ID: 1) ===\n";
// Отправляем DELETE-запрос для удаления комментария с ID=1
$deleteResult = $apiClient->delete('/comments/1');

// Обработка результата удаления
if ($deleteResult['success']) {
    echo "Комментарий успешно удален!\n";
} else {
    echo "Ошибка при удалении: {$deleteResult['status']}\n";
    if ($deleteResult['error']) {
        echo "Детали: {$deleteResult['error']}\n";
    }
}

// Вывод статистики по выполненным запросам
echo "\n=== Статистика запросов ===\n";
echo "Всего выполнено запросов: 4\n";
// Подсчет успешных запросов с использованием тернарных операторов
echo "Успешных: " . 
    ($comments['success'] ? 1 : 0) +  // GET запрос
    ($createResult['success'] ? 1 : 0) +  // POST запрос
    ($updateResult['success'] ? 1 : 0) +  // PUT запрос
    ($deleteResult['success'] ? 1 : 0) . "\n";  // DELETE запрос