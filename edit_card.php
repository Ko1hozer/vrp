<?php
session_start();
require 'includes/db.php'; // Подключение к базе данных
require 'includes/functions.php'; // Подключение файла с функциями

$message = '';

// Получение ID карты из URL
$cardId = $_GET['id'] ?? null;

// Если ID не передан, перенаправляем пользователя обратно на страницу с картами
if (!$cardId) {
    header('Location: cards.php');
    exit();
}

// Получение данных карты из базы данных
$card = getCardById($pdo, $cardId);

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Сбор данных из POST-запроса
    $uid = $_POST['uid'] ?? '';
    $organization = $_POST['organization'] ?? '';
    $vehicleNumber = $_POST['vehicle_number'] ?? '';
    $zone = $_POST['zone'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';

    // Обновление информации о карте в базе данных
    $result = updateCard($pdo, $cardId, $uid, $organization, $vehicleNumber, $phoneNumber, $zone, $additionalInfo);

    if ($result) {
        $_SESSION['message'] = "Карта успешно обновлена.";
    } else {
        $_SESSION['message'] = "Произошла ошибка при обновлении карты.";
    }

    header('Location: cards.php');
    exit();
}

// Подключаем header
require 'includes/header.php';
?>

<div class="container">
    <h2>Редактирование карты</h2>

    <?php if ($message): ?>
        <p><?= $message; ?></p>
    <?php endif; ?>

    <form action="edit_card.php?id=<?= $cardId ?>" method="post">
        <div class="form-group">
            <label for="uid">UID карты</label>
            <input type="text" class="form-control" id="uid" name="uid" value="<?= $card['uid'] ?>" required>
        </div>
        <div class="form-group">
            <label for="organization">Организация</label>
            <input type="text" class="form-control" id="organization" name="organization" value="<?= $card['organization'] ?>" required>
        </div>
        <div class="form-group">
            <label for="vehicle_number">Номер машины</label>
            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" value="<?= $card['vehicle_number'] ?>" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Телефон</label>
            <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?= $card['phone_number'] ?>" required>
        </div>
        <div class="form-group">
            <label for="zone">Зона отбора</label>
            <select class="form-control" id="zone" name="zone" required>
                <option value="Чистая" <?= $card['zone'] === 'Чистая' ? 'selected' : '' ?>>Чистая</option>
                <option value="Исходная" <?= $card['zone'] === 'Исходная' ? 'selected' : '' ?>>Исходная</option>
                <option value="Исходная, Чистая" <?= $card['zone'] === 'Исходная, Чистая' ? 'selected' : '' ?>>Исходная, Чистая</option>
            </select>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Обновить</button>
    </form>
</div>

<?php
// Подключаем footer
require 'includes/footer.php';
?>
