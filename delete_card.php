<?php
session_start();
require 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Подготовка SQL-запроса для удаления карты по ID
    $sql = "DELETE FROM cards WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Биндим ID и выполняем запрос
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $result = $stmt->execute();

    // Проверяем результат выполнения запроса
    if ($result) {
        $_SESSION['message'] = "Карта с ID $id успешно удалена.";
    } else {
        $_SESSION['message'] = "Произошла ошибка при удалении карты.";
    }

    // Перенаправляем обратно на страницу с картами
    header('Location: cards.php');
    exit();
} else {
    die('Ошибка: ID карты не указан');
}
