<?php
require '../../core/main.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Перевірка, чи заповнені всі необхідні поля
    if (!isset($data['userId']) || !isset($data['roleId']) || !isset($data['token'])) {
        //http_response_code(400);
        echo json_encode(['error' => 'Не всі необхідні поля заповнені']);
        return;
    }

    // Отримання ролі користувача за токеном
    $userRole = getUserRole($data['token']);

    if ($userRole !== $roles['admin']) {
        //http_response_code(403);
        echo json_encode(['error' => 'Недостатньо прав для виконання цієї дії']);
        return;
    }

    $db = getDB();
    $stmt = $db->prepare('UPDATE Users SET RoleID = :roleId WHERE UserID = :userId');
    $stmt->bindValue(':roleId', $data['roleId'], SQLITE3_INTEGER);
    $stmt->bindValue(':userId', $data['userId'], SQLITE3_INTEGER);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['message' => 'Роль користувача успішно змінена']);
    } else {
        //http_response_code(500);
        echo json_encode(['error' => 'Сталася помилка під час оновлення ролі користувача']);
    }
} else {
    //http_response_code(405);
    echo json_encode(['error' => 'Метод не дозволено']);
}