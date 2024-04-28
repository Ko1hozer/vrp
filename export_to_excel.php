<?php
require 'vendor/autoload.php';
require 'includes/db.php'; // Подключение к базе данных

if (isset($_POST['export_to_excel'])) {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Установка заголовков столбцов
    $headers = ['Дата/Время', 'Организация', 'Номер машины', 'Зона', 'Расход воды (м³)'];
    $sheet->fromArray($headers, NULL, 'A1');

    // Построение запроса с учетом фильтров
    $filterQuery = " WHERE 1=1 ";
    $params = [];

    if (!empty($_POST['organization'])) {
        $filterQuery .= " AND organization = ?";
        $params[] = $_POST['organization'];
    }

    if (!empty($_POST['zone'])) {
        $filterQuery .= " AND zone = ?";
        $params[] = $_POST['zone'];
    }

    if (!empty($_POST['date_range'])) {
        list($date_from, $date_to) = explode(" to ", $_POST['date_range']);
        $filterQuery .= " AND date_time BETWEEN ? AND ?";
        $params[] = $date_from;
        $params[] = $date_to;
    }

    $query = "SELECT date_time, organization, vehicle_number, zone, water_consumption FROM water_usage_log" . $filterQuery . " ORDER BY date_time DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Заполнение данных
    $rowNumber = 2;
    foreach ($rows as $row) {
        $sheet->fromArray($row, NULL, 'A' . $rowNumber++);
    }

    // Выгрузка файла Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="История потребления.xlsx"');
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
}
?>
