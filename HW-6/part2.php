<?php
/**
 * Примеры работы с API через cURL с различными типами запросов
 * Демонстрирует GET с заголовками, POST с JSON и GET с URL-параметрами
 */

// =============================================
// 1. GET-ЗАПРОС С ПОЛЬЗОВАТЕЛЬСКИМИ ЗАГОЛОВКАМИ
// =============================================
$getCh = curl_init(); // Инициализация cURL-сессии

// URL для получения списка комментариев
$getUrl = 'https://jsonplaceholder.typicode.com/comments';
curl_setopt($getCh, CURLOPT_URL, $getUrl); // Установка целевого URL

// Пользовательские заголовки для запроса
$customHeaders = [
    'X-Custom-Data: SampleHeaderValue', // Произвольный пользовательский заголовок
    'Accept-Language: en-US'            // Указание предпочитаемого языка
];

// Настройка параметров запроса:
curl_setopt($getCh, CURLOPT_HTTPHEADER, $customHeaders); // Установка заголовков
curl_setopt($getCh, CURLOPT_SSL_VERIFYHOST, 0);         // Отключение проверки SSL хоста
curl_setopt($getCh, CURLOPT_SSL_VERIFYPEER, false);     // Отключение проверки SSL сертификата
curl_setopt($getCh, CURLOPT_RETURNTRANSFER, 1);         // Возврат результата в виде строки

$getResponse = curl_exec($getCh); // Выполнение запроса

// Обработка возможных ошибок
if (curl_errno($getCh)) {
    echo 'Произошла ошибка при GET-запросе: ' . curl_error($getCh);
}

curl_close($getCh); // Закрытие сессии

// Вывод результатов
echo "=== GET с заголовками ===\n";
echo $getResponse . "\n\n";

// =============================================
// 2. POST-ЗАПРОС С JSON-ДАННЫМИ
// =============================================
$postCh = curl_init(); // Инициализация новой cURL-сессии

// URL для создания нового комментария
$postUrl = 'https://jsonplaceholder.typicode.com/comments';
curl_setopt($postCh, CURLOPT_URL, $postUrl);

// Данные для отправки в формате массива
$postPayload = [
    'postId' => 15,                             // ID связанного поста
    'name' => 'API Test',                       // Название комментария
    'email' => 'api.test@example.com',         // Email автора
    'body' => 'Тестовый комментарий через JSON POST' // Текст комментария
];

$jsonPayload = json_encode($postPayload); // Кодирование данных в JSON

// Настройка POST-запроса:
curl_setopt($postCh, CURLOPT_POST, 1);                      // Указываем метод POST
curl_setopt($postCh, CURLOPT_POSTFIELDS, $jsonPayload);     // Устанавливаем тело запроса
curl_setopt($postCh, CURLOPT_HTTPHEADER, [                  // Устанавливаем заголовки
    'Content-Type: application/json; charset=UTF-8',        // Тип содержимого - JSON
    'Content-Length: ' . strlen($jsonPayload)              // Длина содержимого
]);
curl_setopt($postCh, CURLOPT_RETURNTRANSFER, true);        // Возврат результата как строки
curl_setopt($postCh, CURLOPT_SSL_VERIFYPEER, 0);           // Отключение проверки SSL

$postResponse = curl_exec($postCh); // Выполнение запроса

// Обработка ошибок
if (curl_errno($postCh)) {
    echo 'Ошибка POST-запроса: ' . curl_error($postCh);
}

curl_close($postCh); // Закрытие сессии

// Вывод результатов
echo "=== POST с JSON ===\n";
echo $postResponse . "\n\n";

// =============================================
// 3. GET-ЗАПРОС С URL-ПАРАМЕТРАМИ
// =============================================
$paramCh = curl_init(); // Инициализация новой cURL-сессии

// Базовый URL для запроса комментариев
$baseUrl = 'https://jsonplaceholder.typicode.com/comments';

// Параметры для фильтрации результатов
$queryParams = [
    'postId' => 5,  // Фильтр по ID поста
    '_limit' => 3   // Ограничение количества результатов
];

// Формирование полного URL с параметрами
$fullUrl = $baseUrl . '?' . http_build_query($queryParams);

// Настройка параметров запроса:
curl_setopt($paramCh, CURLOPT_URL, $fullUrl);           // Установка URL с параметрами
curl_setopt($paramCh, CURLOPT_RETURNTRANSFER, 1);      // Возврат результата как строки
curl_setopt($paramCh, CURLOPT_SSL_VERIFYPEER, false);  // Отключение проверки SSL

$paramResponse = curl_exec($paramCh); // Выполнение запроса

// Обработка ошибок
if (curl_errno($paramCh)) {
    echo 'Ошибка при запросе с параметрами: ' . curl_error($paramCh);
}

curl_close($paramCh); // Закрытие сессии

// Вывод результатов
echo "=== GET с параметрами ===\n";
echo $paramResponse . "\n";

// =============================================
// ВЫВОД СТАТИСТИКИ ПО ЗАПРОСАМ
// =============================================
echo "\n=== Информация о запросах ===\n";
echo "GET запрос: " . strlen($getResponse) . " байт\n";         // Размер GET-ответа
echo "POST запрос: " . strlen($postResponse) . " байт\n";       // Размер POST-ответа
echo "GET с параметрами: " . strlen($paramResponse) . " байт\n"; // Размер ответа с параметрами
?>