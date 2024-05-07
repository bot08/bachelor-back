<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "
        SELECT
            ModelID,
            ModelManufacturer,
            ModelName,
            ModelImage,
            ModelPolarization,
            ModelDescription,
            ModelPrice
        FROM SunglassesModels
    ";

    $conditions = [];

    // Фільтр за виробником
    if (isset($_GET['manufacturer'])) {
        $conditions[] = "ModelManufacturer LIKE '%' || :manufacturer || '%'";
    }
    // Фільтр за назвою моделі
    if (isset($_GET['name'])) {
        $conditions[] = "ModelName LIKE '%' || :name || '%'";
    }
    // Фільтр за наявністю поляризації
    if (isset($_GET['polarized'])) {
        $conditions[] = "ModelPolarization = :polarized";
    }
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }
    // Обмеження кількості елементів
    if (isset($_GET['limit'])) {
        $query .= " LIMIT :limit";
    }
    // Пропуск певної кількості елементів
    if (isset($_GET['offset'])) {
        $query .= " OFFSET :offset";
    }
    // Сортування за ціною
    if (isset($_GET['sort']) && $_GET['sort'] === 'price') {
        $query .= " ORDER BY ModelPrice";
        if (isset($_GET['order']) && $_GET['order'] === 'desc') {
            $query .= " DESC";
        } else {
            $query .= " ASC";
        }
    }

    $stmt = $db->prepare($query);

    // Пов'язування значень параметрів запиту
    if (isset($_GET['manufacturer'])) {
        $stmt->bindValue(':manufacturer', $_GET['manufacturer'], SQLITE3_TEXT);
    }
    if (isset($_GET['name'])) {
        $stmt->bindValue(':name', $_GET['name'], SQLITE3_TEXT);
    }
    if (isset($_GET['polarized'])) {
        $stmt->bindValue(':polarized', $_GET['polarized'] === 'true', SQLITE3_INTEGER);
    }
    if (isset($_GET['limit'])) {
        $stmt->bindValue(':limit', (int)$_GET['limit'], SQLITE3_INTEGER);
    }
    if (isset($_GET['offset'])) {
        $stmt->bindValue(':offset', (int)$_GET['offset'], SQLITE3_INTEGER);
    }

    $result = $stmt->execute();

    $sunglasses = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $sunglasses[] = $row;
    }

    echo json_encode($sunglasses);
}