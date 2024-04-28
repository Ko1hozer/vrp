<?php
session_start(); // Начало сессии
require 'includes/db.php';
require 'includes/functions.php';
require 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $uid = $_POST['uid'] ?? '';
    $organization = $_POST['organization'] ?? '';
    $vehicleNumber = $_POST['vehicle_number'] ?? '';
    $zone = $_POST['zone'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';
    $additionalInfo = $_POST['additional_info'] ?? ''; // Убедитесь, что это поле существует в вашей форме

    // Проверяем, не является ли какое-либо из полей пустым перед сохранением
    if (!empty($uid) && !empty($organization) && !empty($vehicleNumber) && !empty($zone) && !empty($phoneNumber)) {
        $result = registerCard($pdo, $uid, $organization, $vehicleNumber, $phoneNumber, $zone, $additionalInfo);
        // Сохраняем сообщение в сессию, чтобы показать его после перенаправления
        $_SESSION['message'] = $result ? "Карта успешно добавлена." : "Произошла ошибка при добавлении карты.";
        // Перенаправляем на ту же страницу для предотвращения повторной отправки формы
        header('Location: cards.php');
        exit();
    } else {
        // Сохраняем сообщение об ошибке в сессию
        $_SESSION['message'] = "Все поля должны быть заполнены.";
        header('Location: cards.php');
        exit();
    }
}

// Проверяем, есть ли сообщение в сессии, и выводим его
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Удаляем сообщение из сессии после его отображения
}

$cards = getAllCards($pdo);
?>

<div class="container mt-3">
    <h2>Управление картами</h2>
    
    <!-- Кнопка для открытия модального окна регистрации новой карты -->
    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#registerCardModal">
        Зарегистрировать карту
    </button>
    
    <div class="clearfix"></div> <!-- Для корректного отображения кнопки -->

    <!-- Модальное окно для регистрации новой карты -->
    <div class="modal hidden-modal" id="registerCardModal" tabindex="-1" role="dialog" aria-labelledby="registerCardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="cards.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerCardModalLabel">Регистрация новой карты</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- UID -->
                    <div class="form-group">
                        <label for="uid">UID карты</label>
                        <input type="text" class="form-control" id="uid" name="uid" required>
                    </div>
                    <!-- Название организации -->
                    <div class="form-group">
                        <label for="organization">Организация</label>
                        <input type="text" class="form-control" id="organization" name="organization" required>
                    </div>
                    <!-- Номер машины -->
                    <div class="form-group">
                        <label for="vehicle_number">Номер машины</label>
                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" required>
                    </div>
                    <!-- Зона отбора -->
                    <div class="form-group">
                        <label for="zone">Зона отбора</label>
                        <select class="form-control" id="zone" name="zone" required>
                            <option value="">Выберите зону</option>
                            <option value="Чистая">Чистая</option>
                            <option value="Исходная">Исходная</option>
                            <option value="Исходная, Чистая">Исходная, Чистая</option>
                        </select>
                    </div>
                    <!-- Телефон организации -->
                    <div class="form-group">
                        <label for="phone_number">Телефон организации</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Зарегистрировать</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Таблица зарегистрированных карт -->
    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>UID</th>
                <th>Организация</th>
                <th>Номер машины</th>
                <th>Телефон</th>
                <th>Зона отбора</th>
                <th>Изменение</th>
                <!-- Дополнительные заголовки таблицы по необходимости -->
            </tr>
            <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
        </thead>
        <tbody>
    <?php foreach ($cards as $card): ?>
        <tr>
            <td><?= htmlspecialchars($card['id']) ?></td>
            <td><?= htmlspecialchars($card['uid']) ?></td>
            <td><?= htmlspecialchars($card['organization']) ?></td>
            <td><?= htmlspecialchars($card['vehicle_number']) ?></td>
            <td><?= htmlspecialchars($card['phone_number']) ?></td>
            <td><?= htmlspecialchars($card['zone']) ?></td>
            <td>
            <a href="edit_card.php?id=<?= $card['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
            <a href="delete_card.php?id=<?= $card['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить эту карту?');"><i class="fas fa-trash-alt"></i></a>

        </td>
        </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</div>

<?php require 'includes/footer.php'; ?>
