<?php
define('DB_PATH', realpath(dirname(__FILE__) . '/../database/index.db'));
// Підключення до БД
$db = new SQLite3(DB_PATH);

// Заголовки
http_response_code(200);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Основні ролі
$roles = array(
    'user' => 0,
    'editor' => 1,
    'admin' => 2
);


function getDB() {
    global $db;
    return $db;
}

function getUserRole($token) {
    $db = getDB();
    $stmt = $db->prepare('SELECT RoleID FROM Users WHERE Token = :token');
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        return $row['RoleID'];
    }

    return false;
}