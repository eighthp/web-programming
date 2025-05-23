<?php
/**
 * Демонстрация работы с API через cURL
 * Примеры GET, POST, PUT и DELETE запросов к JSONPlaceholder API
 */

// =============================================
// 1. GET-ЗАПРОС - ПОЛУЧЕНИЕ ДАННЫХ О КОММЕНТАРИИ
// =============================================
$getUrl = "https://jsonplaceholder.typicode.com/comments/5"; // URL для получения комментария с ID=5
$getCh = curl_init(); // Инициализация cURL-сессии

// Настройка параметров GET-запроса:
curl_setopt($getCh, CURLOPT_URL, $getUrl); // Устанавливаем URL
curl_setopt($getCh, CURLOPT_SSL_VERIFYHOST, false); // Отключаем проверку SSL (не для production!)
curl_setopt($getCh, CURLOPT_SSL_VERIFYPEER, false); // Отключаем проверку SSL (не для production!)
curl_setopt($getCh, CURLOPT_RETURNTRANSFER, true); // Возвращать результат вместо вывода
curl_setopt($getCh, CURLOPT_HEADER, false); // Не включать заголовки в вывод

$getResponse = curl_exec($getCh); // Выполняем запрос
curl_close($getCh); // Закрываем сессию

// Вывод результата:
echo "=== GET Response ===\n";
echo $getResponse . "\n\n";

// =============================================
// 2. POST-ЗАПРОС - СОЗДАНИЕ НОВОГО КОММЕНТАРИЯ
// =============================================
$postUrl = "https://jsonplaceholder.typicode.com/comments"; // URL для создания комментария
$postCh = curl_init();

// Данные для нового комментария:
$postData = [
    'postId' => 5, // ID связанного поста
    'name' => 'New Comment', // Название комментария
    'email' => 'user@example.net', // Email автора
    'body' => 'This is a test comment created via cURL' // Текст комментария
];

// Настройка POST-запроса:
curl_setopt($postCh, CURLOPT_URL, $postUrl);
curl_setopt($postCh, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($postCh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($postCh, CURLOPT_POST, true); // Указываем, что это POST-запрос
curl_setopt($postCh, CURLOPT_HTTPHEADER, [ // Устанавливаем заголовки
    'Content-Type: application/json', // Отправляем данные в формате JSON
    'Accept: application/json' // Ожидаем JSON в ответ
]);
curl_setopt($postCh, CURLOPT_POSTFIELDS, json_encode($postData)); // Кодируем данные в JSON

$postResponse = curl_exec($postCh); // Выполняем запрос
$postInfo = curl_getinfo($postCh); // Получаем информацию о запросе
curl_close($postCh); // Закрываем сессию

// Вывод результата:
echo "=== POST Response ===\n";
echo "HTTP Status: " . $postInfo['http_code'] . "\n"; // Код статуса HTTP
echo $postResponse . "\n\n";

// =============================================
// 3. PUT-ЗАПРОС - ОБНОВЛЕНИЕ КОММЕНТАРИЯ
// =============================================
$putUrl = "https://jsonplaceholder.typicode.com/comments/5"; // URL для обновления комментария с ID=5
$putCh = curl_init();

// Данные для обновления:
$putData = [
    'id' => 5, // ID комментария (должен совпадать с URL)
    'postId' => 5, // ID поста
    'name' => 'Updated Comment', // Новое название
    'email' => 'updated@example.org', // Новый email
    'body' => 'This comment has been modified via PUT request' // Новый текст
];

// Настройка PUT-запроса:
curl_setopt($putCh, CURLOPT_URL, $putUrl);
curl_setopt($putCh, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($putCh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($putCh, CURLOPT_CUSTOMREQUEST, 'PUT'); // Указываем метод PUT
curl_setopt($putCh, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json' // Отправляем JSON
]);
curl_setopt($putCh, CURLOPT_POSTFIELDS, json_encode($putData)); // Кодируем данные

$putResponse = curl_exec($putCh); // Выполняем запрос
$putInfo = curl_getinfo($putCh); // Получаем информацию
curl_close($putCh); // Закрываем сессию

// Вывод результата:
echo "=== PUT Response ===\n";
echo "HTTP Status: " . $putInfo['http_code'] . "\n";
echo $putResponse . "\n\n";

// =============================================
// 4. DELETE-ЗАПРОС - УДАЛЕНИЕ КОММЕНТАРИЯ
// =============================================
$deleteUrl = "https://jsonplaceholder.typicode.com/comments/5"; // URL для удаления комментария с ID=5
$deleteCh = curl_init();

// Настройка DELETE-запроса:
curl_setopt($deleteCh, CURLOPT_URL, $deleteUrl);
curl_setopt($deleteCh, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($deleteCh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($deleteCh, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Указываем метод DELETE

$deleteResponse = curl_exec($deleteCh); // Выполняем запрос
$deleteInfo = curl_getinfo($deleteCh); // Получаем информацию

// Проверка на ошибки:
if(curl_errno($deleteCh)) {
    echo 'cURL Error: ' . curl_error($deleteCh) . "\n";
}

curl_close($deleteCh); // Закрываем сессию

// Вывод результата:
echo "=== DELETE Response ===\n";
echo "HTTP Status: " . $deleteInfo['http_code'] . "\n";
echo $deleteResponse . "\n";

// =============================================
// ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ О ЗАПРОСАХ
// =============================================
echo "\n=== Request Details ===\n";
echo "Total Time: " . $deleteInfo['total_time'] . " seconds\n"; // Общее время выполнения
echo "Request Size: " . $deleteInfo['request_size'] . " bytes\n"; // Размер запроса
?>