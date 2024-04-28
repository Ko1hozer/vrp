<?php
// Конфигурация подключения к базе данных
$host = 'localhost';  // Адрес сервера базы данных
$dbname = 'vrp_gdn';  // Имя базы данных
$username = 'root';  // Имя пользователя базы данных
$password = '';  // Пароль пользователя базы данных

try {
    // Создание объекта PDO и установка соединения
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Установка атрибута для генерации исключений при ошибках в запросах
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

} catch (PDOException $e) {
    // В случае ошибки вывод сообщения
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}
?>
