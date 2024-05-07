<?php
require '../../core/main.php';

function authenticate($username, $password) {
    $db = getDB();
    $stmt = $db->prepare('SELECT UserID, Password FROM Users WHERE Username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row && password_verify($password, $row['Password'])) {
        return $row['UserID'];
    } else {
        return false;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Перевірка, чи заповнені всі необхідні поля
    if (!isset($data['username']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Не всі необхідні поля заповнені']);
        return;
    }

    // Спроба авторизації користувача
    $userId = authenticate($data['username'], $data['password']);

    if ($userId) {
        $db = getDB();
        $stmt = $db->prepare('SELECT Token, RoleID FROM Users WHERE UserID = :userId');
        $stmt->bindValue(':userId', $userId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row) {
            echo json_encode([
                'message' => 'Успішний логін',
                'token' => $row['Token'],
                'roleId' => $row['RoleID']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Сталася помилка під час отримання даних користувача']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Невірне імя користувача або пароль']);
    }
} 
else {
    http_response_code(405);
}