<?php
require '../../core/main.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $db = getDB();

    $result = $db->query('SELECT * FROM Users');
    $users = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $users[] = $row;
    }
    echo json_encode($users);
}