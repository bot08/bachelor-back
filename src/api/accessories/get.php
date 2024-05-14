<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "
        SELECT
            AccessoryID,
            AccessoryManufacturer,
            AccessoryImage,
            AccessoryName,
            AccessoryDescription,
            AccessoryPrice
        FROM Accessories
    ";

    $conditions = [];

    // Вивід з певним id
    if (isset($_GET['id'])) {
        $conditions[] = "AccessoryID = :id";
    }
    // Фільтр за виробником
    if (isset($_GET['manufacturer'])) {
        $conditions[] = "AccessoryManufacturer LIKE '%' || :manufacturer || '%'";
    }
    // Фільтр за назвою
    if (isset($_GET['name'])) {
        $conditions[] = "AccessoryName LIKE '%' || :name || '%'";
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
        $query .= " ORDER BY AccessoryPrice";
        if (isset($_GET['order']) && $_GET['order'] === 'desc') {
            $query .= " DESC";
        } else {
            $query .= " ASC";
        }
    }

    $stmt = $db->prepare($query);

    // Пов'язування значень параметрів запиту
    if (isset($_GET['id'])) {
        $stmt->bindValue(':id', (int)$_GET['id'], SQLITE3_INTEGER);
    }
    if (isset($_GET['manufacturer'])) {
        $stmt->bindValue(':manufacturer', $_GET['manufacturer'], SQLITE3_TEXT);
    }
    if (isset($_GET['name'])) {
        $stmt->bindValue(':name', $_GET['name'], SQLITE3_TEXT);
    }
    if (isset($_GET['limit'])) {
        $stmt->bindValue(':limit', (int)$_GET['limit'], SQLITE3_INTEGER);
    }
    if (isset($_GET['offset'])) {
        $stmt->bindValue(':offset', (int)$_GET['offset'], SQLITE3_INTEGER);
    }

    $result = $stmt->execute();

    $accessories = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $accessories[] = $row;
    }

    echo json_encode($accessories);
}