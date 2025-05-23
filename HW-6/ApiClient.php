<?php
/**
 * Класс для работы с REST API
 * Предоставляет удобные методы для отправки HTTP-запросов (GET, POST, PUT, PATCH, DELETE)
 * с поддержкой Basic Auth и автоматической обработкой JSON
 */
class RestApiClient
{
    // Базовый URL API
    private string $baseUrl;
    
    // Массив заголовков по умолчанию для всех запросов
    private array $defaultHeaders = [];
    
    // Учетные данные для Basic Auth
    private array $authCredentials = [];

    /**
     * Конструктор класса
     * $apiBaseUrl Базовый URL API (например, "https://api.example.com")
     * $username Имя пользователя для Basic Auth (необязательно)
     * $password Пароль для Basic Auth (необязательно)
     */
    public function __construct(string $apiBaseUrl, string $username = '', string $password = '')
    {
        // Удаляем слеш в конце URL, если он есть
        $this->baseUrl = rtrim($apiBaseUrl, '/');
        
        // Устанавливаем заголовки по умолчанию для JSON
        $this->defaultHeaders = [
            'Accept: application/json',
            'Content-Type: application/json'
        ];
        
        // Если переданы учетные данные, устанавливаем Basic Auth
        if (!empty($username)) {
            $this->setBasicAuth($username, $password);
        }
    }

    /**
     * Установка Basic Auth для всех последующих запросов
     * $username Имя пользователя
     * $password Пароль
     */
    public function setBasicAuth(string $username, string $password): void
    {
        $this->authCredentials = [
            'username' => $username,
            'password' => $password
        ];
    }

    /**
     * Внутренний метод для отправки HTTP-запроса
     * $method HTTP-метод (GET, POST, PUT, PATCH, DELETE)
     * $endpoint Конечная точка API (например, "/users")
     * $data Тело запроса (для POST, PUT, PATCH)
     * Массив с результатом запроса
     **/
    private function sendRequest(
        string $method, 
        string $endpoint, 
        array $data = null
    ): array {
        // Формируем полный URL
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        
        // Инициализируем cURL-сессию
        $ch = curl_init($url);

        // Базовые настройки cURL
        $options = [
            CURLOPT_RETURNTRANSFER => true,  // Возвращать результат вместо вывода
            CURLOPT_CUSTOMREQUEST => strtoupper($method),  // Устанавливаем метод
            CURLOPT_SSL_VERIFYHOST => false, // Отключаем проверку SSL (не рекомендуется для production)
            CURLOPT_SSL_VERIFYPEER => false, // Отключаем проверку SSL (не рекомендуется для production)
            CURLOPT_HTTPHEADER => $this->defaultHeaders, // Устанавливаем заголовки
            CURLOPT_TIMEOUT => 30,           // Таймаут запроса 30 секунд
        ];

        // Если установлены учетные данные, добавляем Basic Auth
        if (!empty($this->authCredentials)) {
            $options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $options[CURLOPT_USERPWD] = $this->authCredentials['username'] . ':' . $this->authCredentials['password'];
        }

        // Если есть данные для отправки (для POST, PUT, PATCH)
        if (!empty($data)) {
            $jsonData = json_encode($data);
            $options[CURLOPT_POSTFIELDS] = $jsonData;
            // Добавляем заголовок с длиной содержимого
            $this->defaultHeaders[] = 'Content-Length: ' . strlen($jsonData);
        }

        // Устанавливаем все опции для cURL
        curl_setopt_array($ch, $options);

        // Выполняем запрос
        $response = curl_exec($ch);
        // Получаем HTTP-код ответа
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // Получаем ошибку, если есть
        $error = curl_error($ch);
        
        // Закрываем cURL-сессию
        curl_close($ch);

        // Пытаемся декодировать JSON-ответ
        $decodedResponse = null;
        if (!empty($response)) {
            $decodedResponse = json_decode($response, true);
            // Если не удалось декодировать JSON, возвращаем сырой ответ
            if (json_last_error() !== JSON_ERROR_NONE) {
                $decodedResponse = $response;
            }
        }

        // Возвращаем структурированный результат
        return [
            'status' => $httpCode,       // HTTP-статус код
            'data' => $decodedResponse,  // Данные ответа (декодированный JSON или сырой ответ)
            'error' => $error ?: null,   // Текст ошибки или null
            'success' => $httpCode >= 200 && $httpCode < 300  // Успешен ли запрос (код 2xx)
        ];
    }

    /**
     * Отправка GET-запроса
     * @param string $endpoint Конечная точка API
     * @return array Результат запроса
     */
    public function get(string $endpoint): array
    {
        return $this->sendRequest('GET', $endpoint);
    }

    /**
     * Отправка POST-запроса
     * @param string $endpoint Конечная точка API
     * @param array $data Данные для отправки
     * @return array Результат запроса
     */
    public function post(string $endpoint, array $data): array
    {
        return $this->sendRequest('POST', $endpoint, $data);
    }

    /**
     * Отправка PUT-запроса
     * @param string $endpoint Конечная точка API
     * @param array $data Данные для отправки
     * @return array Результат запроса
     */
    public function put(string $endpoint, array $data): array
    {
        return $this->sendRequest('PUT', $endpoint, $data);
    }

    /**
     * Отправка PATCH-запроса
     * @param string $endpoint Конечная точка API
     * @param array $data Данные для отправки
     * @return array Результат запроса
     **/
    public function patch(string $endpoint, array $data): array
    {
        return $this->sendRequest('PATCH', $endpoint, $data);
    }

    /**
     * Отправка DELETE-запроса
     * @param string $endpoint Конечная точка API
     * @return array Результат запроса
     */
    public function delete(string $endpoint): array
    {
        return $this->sendRequest('DELETE', $endpoint);
    }
}