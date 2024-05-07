<?php
require '../../core/main.php';

function register($username, $password, $email, $fullName) {
    $db = getDB();
    $stmt = $db->prepare('INSERT INTO Users (Username, Password, Email, FullName, Token, RoleID) VALUES (:username, :password, :email, :fullName, :token, 0)');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':fullName', $fullName, SQLITE3_TEXT);
    $stmt->bindValue(':token', bin2hex(random_bytes(32)), SQLITE3_TEXT);
    $stmt->execute();
    return $db->lastInsertRowID();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Перевірка, чи заповнені всі необхідні поля
    if (!isset($data['username']) || !isset($data['password']) || !isset($data['email']) || !isset($data['fullName'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Не всі необхідні поля заповнені']);
        return;
    }

    // Перевірка унікальності імені користувача та електронної пошти
    $db = getDB();
    $stmt = $db->prepare('SELECT COUNT(*) FROM Users WHERE Username = :username OR Email = :email');
    $stmt->bindValue(':username', $data['username'], SQLITE3_TEXT);
    $stmt->bindValue(':email', $data['email'], SQLITE3_TEXT);
    $result = $stmt->execute();
    $count = $result->fetchArray(SQLITE3_NUM)[0];

    if ($count > 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Імʼя користувача або електронна пошта вже зайняті']);
        return;
    }

    // Реєстрація нового користувача
    $userId = register($data['username'], $data['password'], $data['email'], $data['fullName']);

    if ($userId) {
        echo json_encode(['message' => 'Користувач успішно зареєстрований', 'userId' => $userId]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Сталася помилка під час реєстрації']);
    }
} 
else {
    http_response_code(405);
}