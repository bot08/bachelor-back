<?php
require '../../core/main.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Перевірка наявності токена в заголовках запиту
    if (!isset($_GET['token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Токен не надано']);
        return;
    }

    $token = $_GET['token'];


    // Отримання ролі користувача за токеном
    $roleId = getUserRole($token);

    if ($roleId === false) {
        //http_response_code(401);
        echo json_encode(['error' => 'Невірний токен']);
        return;
    }

    $db = getDB();
    $stmt = $db->prepare('SELECT UserID, Username, Email, FullName, RoleID FROM Users WHERE Token = :token');
    $stmt->bindValue(':token', $token, SQLITE3_TEXT);
    $result = $stmt->execute();
    $userData = $result->fetchArray(SQLITE3_ASSOC);

    if ($userData) {
        echo json_encode($userData);
    } else {
        //http_response_code(404);
        echo json_encode(['error' => 'Користувач не знайдений']);
    }
} else {
    //http_response_code(405);
    echo json_encode(['error' => 'Метод не дозволено']);
}