<?php
// Функция для регистрации новой карты
function registerCard($pdo, $uid, $organization, $vehicleNumber, $phoneNumber, $zone, $additionalInfo) {
    // Очистка входных данных
    $uid = trim($uid);
    $organization = trim($organization);
    $vehicleNumber = trim($vehicleNumber);
    $phoneNumber = trim($phoneNumber);
    $zone = trim($zone);
    $additionalInfo = trim($additionalInfo);

    // Проверка на пустые значения
    if (empty($uid) || empty($organization) || empty($vehicleNumber) || empty($phoneNumber) || empty($zone)) {
        return false;
    }

    // Подготовленный запрос для предотвращения SQL-инъекций
    $sql = "INSERT INTO cards (uid, organization, vehicle_number, phone_number, zone, additional_info) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$uid, $organization, $vehicleNumber, $phoneNumber, $zone, $additionalInfo]);
}

// Функция для редактирования информации о карте
function updateCard($pdo, $id, $uid, $organization, $vehicleNumber, $phoneNumber, $zone, $additionalInfo) {
    // Очистка входных данных
    $id = trim($id);
    $uid = trim($uid);
    $organization = trim($organization);
    $vehicleNumber = trim($vehicleNumber);
    $phoneNumber = trim($phoneNumber);
    $zone = trim($zone);
    $additionalInfo = trim($additionalInfo);

    // Подготовленный запрос для предотвращения SQL-инъекций
    $sql = "UPDATE cards SET uid = ?, organization = ?, vehicle_number = ?, phone_number = ?, zone = ?, additional_info = ? 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$uid, $organization, $vehicleNumber, $phoneNumber, $zone, $additionalInfo, $id]);
}


// Функция для получения информации о всех картах
function getAllCards($pdo) {
    $sql = "SELECT id, uid, organization, vehicle_number, phone_number, zone, additional_info FROM cards";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функция для удаления карточки по ID
function deleteCard($pdo, $id) {
    $sql = "DELETE FROM cards WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}


// Функция для получения данных одной карты по её ID
function getCardById($pdo, $id) {
    $sql = "SELECT * FROM cards WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


?>