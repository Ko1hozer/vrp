// Примерный код для страницы видеонаблюдения (videomonitoring.php)
<?php
require 'includes/db.php'; // Подключение к базе данных
require 'includes/header.php'; // Подключение шапки сайта
// ... Код для получения URL-адресов из БД ...
?>
<div class="container mt-3">
    <h2>Видеонаблюдение</h2>
    <div class="camera-feeds">
        <!-- Поток камеры 1 -->
        <div class="camera">
            <h3>Камера 1</h3>
            <iframe src="" frameborder="0" allowfullscreen></iframe>
        </div>
        <!-- Потоки для камер 2 и 3 и т.д. -->
    </div>
</div>
<?php require 'includes/footer.php'; ?>
