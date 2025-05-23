<?php

// URL API, откуда мы будем получать данные (в данном случае конкретный комментарий)
$apiUrl = 'https://jsonplaceholder.typicode.com/comments/5';

// Инициализируем cURL-сессию
$curl = curl_init();

// Настраиваем параметры cURL:
// Отключаем проверку имени хоста для SSL (небезопасно для продакшена!)
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
// Отключаем проверку сертификата SSL (небезопасно для продакшена!)
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
// Устанавливаем URL для запроса
curl_setopt($curl, CURLOPT_URL, $apiUrl); 
// Указываем, что мы хотим получить результат в виде строки, а не выводить напрямую
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
// Устанавливаем метод запроса (GET - получение данных)
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); 

// Выполняем запрос и сохраняем ответ
$apiResponse = curl_exec($curl); 

// Проверяем на ошибки cURL
if(curl_errno($curl)) {
    // Выводим ошибку, если она есть
    echo 'cURL ошибка: ' . curl_error($curl); 
    // Закрываем cURL-сессию
    curl_close($curl);
    // Завершаем выполнение скрипта
    exit; 
}

// Получаем HTTP-статус код ответа
$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
// Закрываем cURL-сессию
curl_close($curl); 

// Обрабатываем ответ в зависимости от HTTP-статуса
switch($statusCode) {
    case 200: // Успешный запрос
        // Пытаемся декодировать JSON-ответ
        $responseData = json_decode($apiResponse);
        
        // Проверяем на ошибки декодирования JSON
        if(json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON декодирование не удалось: ' . json_last_error_msg();
            exit;
        }
        
        // Выводим успешный ответ
        echo "Успешный ответ (200):\n";
        print_r($responseData);
        break;
        
    case 404: // Ресурс не найден
        echo "Ошибка 404\n";
        echo "Ответ сервера: " . $apiResponse;
        break;
        
    case 500: // Ошибка сервера
        echo "Ошибка 500\n";
        echo "Детали: " . $apiResponse;
        break;
        
    default:
        // Обработка других статус-кодов
        if($statusCode >= 400 && $statusCode < 500) {
            // Клиентские ошибки (4xx)
            echo "Клиентская ошибка ({$statusCode}):\n";
            echo $apiResponse;
        } elseif($statusCode >= 500) {
            // Серверные ошибки (5xx)
            echo "Серверная ошибка ({$statusCode}):\n";
            echo $apiResponse;
        } else {
            // Неожиданные статус-коды
            echo "Неожиданный HTTP код: {$statusCode}\n";
            echo "Ответ: " . $apiResponse;
        }
}

// Выводим дополнительную информацию о запросе
echo "\n\n=== Детали запроса ===\n";
echo "HTTP статус: {$statusCode}\n";
echo "Длина ответа: " . strlen($apiResponse) . " байт\n";
?>