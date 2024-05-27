<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "
        SELECT
            LensID,
            LensManufacturer,
            LensManufacturerCountry,
            LensType,
            LensDescription,
            LensPrice
        FROM Lenses
    ";

    $conditions = [];

    // Filter by manufacturer
    if (isset($_GET['manufacturer'])) {
        $conditions[] = "LensManufacturer LIKE '%' || :manufacturer || '%'";
    }
    // Filter by type
    if (isset($_GET['type'])) {
        $conditions[] = "LensType LIKE '%' || :type || '%'";
    }
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }
    // Limit the number of items
    if (isset($_GET['limit'])) {
        $query .= " LIMIT :limit";
    }
    // Offset items
    if (isset($_GET['offset'])) {
        $query .= " OFFSET :offset";
    }
    // Sort by price
    if (isset($_GET['sort']) && $_GET['sort'] === 'price') {
        $query .= " ORDER BY LensPrice";
        if (isset($_GET['order']) && $_GET['order'] === 'desc') {
            $query .= " DESC";
        } else {
            $query .= " ASC";
        }
    }

    $stmt = $db->prepare($query);

    // Bind parameter values
    if (isset($_GET['manufacturer'])) {
        $stmt->bindValue(':manufacturer', $_GET['manufacturer'], SQLITE3_TEXT);
    }
    if (isset($_GET['type'])) {
        $stmt->bindValue(':type', $_GET['type'], SQLITE3_TEXT);
    }
    if (isset($_GET['limit'])) {
        $stmt->bindValue(':limit', (int)$_GET['limit'], SQLITE3_INTEGER);
    }
    if (isset($_GET['offset'])) {
        $stmt->bindValue(':offset', (int)$_GET['offset'], SQLITE3_INTEGER);
    }

    $result = $stmt->execute();

    $lenses = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $lenses[] = $row;
    }

    echo json_encode($lenses);
}