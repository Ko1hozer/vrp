<body>
<div class="content-container">
<?php
require 'includes/db.php'; // Подключение к базе данных

try {
    $orgQuery = "SELECT DISTINCT organization FROM water_usage_log ORDER BY organization";
    $stmt = $pdo->query($orgQuery);
    $organizations = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $organizations = [];
    $error_message = "Ошибка при получении списка организаций: " . $e->getMessage();
}

try {
    $zoneQuery = "SELECT DISTINCT zone FROM water_usage_log ORDER BY zone";
    $zoneStmt = $pdo->query($zoneQuery);
    $zones = $zoneStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $zones = [];
    $error_message .= " Ошибка при получении списка зон: " . $e->getMessage();
}

$filterQuery = " WHERE 1=1 ";
$params = [];

if (!empty($_GET['organization'])) {
    $filterQuery .= " AND organization = :organization";
    $params[':organization'] = $_GET['organization'];
}

if (!empty($_GET['date_range'])) {
    $dates = explode(" to ", $_GET['date_range']);
    if (count($dates) === 2) {
        $filterQuery .= " AND date_time BETWEEN :date_from AND :date_to";
        $params[':date_from'] = $dates[0];
        $params[':date_to'] = $dates[1];
    }
}

if (!empty($_GET['zone'])) {
    $filterQuery .= " AND zone = :zone";
    $params[':zone'] = $_GET['zone'];
}

$query = "SELECT * FROM water_usage_log" . $filterQuery . " ORDER BY date_time DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$history_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalConsumption = ['Чистая' => 0, 'Исходная' => 0];
foreach ($history_entries as $entry) {
    if (strpos($entry['zone'], 'Чистая') !== false) {
        $totalConsumption['Чистая'] += $entry['water_consumption'];
    } else {
        $totalConsumption['Исходная'] += $entry['water_consumption'];
    }
}


include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/flatpickr.min.css">

<main>
    <h2 class="centered-title">История потребления</h2>
    <form method="GET" action="history.php" style="display: inline-block;">
    <form class="filter-form" method="GET" action="history.php">
        Организация: <select name="organization">
            <option value="">Выберите организацию</option>
            <?php foreach ($organizations as $organization): ?>
                <option value="<?= htmlspecialchars($organization) ?>" <?= isset($_GET['organization']) && $_GET['organization'] === $organization ? 'selected' : '' ?>><?= htmlspecialchars($organization) ?></option>
            <?php endforeach; ?>
        </select>
        
        Зона: <select name="zone">
            <option value="">Все зоны</option>
            <?php foreach ($zones as $zone): ?>
                <option value="<?= htmlspecialchars($zone) ?>" <?= isset($_GET['zone']) && $_GET['zone'] === $zone ? 'selected' : '' ?>><?= htmlspecialchars($zone) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" id="date_range" name="date_range" placeholder="Выберите диапазон дат" class="date-range-input">
        <input type="submit" value="Фильтровать" class="filter-btn">
    </form>
    
    <form method="POST" action="export_to_excel.php" style="display: inline-block;">
        <input type="hidden" name="organization" value="<?= $_GET['organization'] ?? '' ?>">
        <input type="hidden" name="zone" value="<?= $_GET['zone'] ?? '' ?>">
        <input type="hidden" name="date_range" value="<?= $_GET['date_range'] ?? '' ?>">
        <input type="submit" name="export_to_excel" value="Экспорт в Excel" class="export-btn">
    </form>

    <div class="table-container">
        <?php if (!empty($history_entries)): ?>
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
                    <?php foreach ($history_entries as $entry): ?>
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
            <div class="total-consumption-container">
                <div class="total-consumption">
                    <table>
                        <thead>
                            <tr><th colspan="2">Общее потребление воды:</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Чистая:</td>
                                <td><?= htmlspecialchars($totalConsumption['Чистая']) ?> м³</td>
                            </tr>
                            <tr>
                                <td>Исходная:</td>
                                <td><?= htmlspecialchars($totalConsumption['Исходная']) ?> м³</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <p>Записи не найдены.</p>
        <?php endif; ?>
        <?php include 'includes/footer.php'; ?>
    </div>
</main>



<script src="assets/js/flatpickr.min.js"></script>
<script>
    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: { firstDayOfWeek: 1 }
    });
</script>


</body>

</html>
