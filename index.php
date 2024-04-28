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

<main>
<h2 class="centered-title">Последние записи потребления воды</h2>
 <div class="table-container">
    <?php if (!empty($latest_entries)): ?>
        <table>
            <thead>
                <tr>
                    <th>Дата/Время</th>
                    <th>Организация</th>
                    <th>Номер машины</th>
                    <th>Зона</th>
                    <th>Расход воды (м³)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($latest_entries as $entry): ?>
                <tr>
                    <td><?= htmlspecialchars($entry['date_time']) ?></td>
                    <td><?= htmlspecialchars($entry['organization']) ?></td>
                    <td><?= htmlspecialchars($entry['vehicle_number']) ?></td>
                    <td><?= htmlspecialchars($entry['zone']) ?></td>
                    <td><?= htmlspecialchars($entry['water_consumption']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php else: ?>
        <p>Записи не найдены.</p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; // Включение подвала страницы ?>
