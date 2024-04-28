<?php
require 'includes/db.php'; // Подключение к базе данных

// Попытка получить последние записи из базы данных
try {
    $stmt = $pdo->query("SELECT * FROM water_usage_log ORDER BY date_time DESC LIMIT 10");
    $latest_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $latest_entries = [];
    $error_message = "Ошибка при получении данных: " . $e->getMessage();
}

include 'includes/header.php'; // Включение заголовка страницы
?>