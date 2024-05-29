<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($token)) {
        $token = isset($data['token']) ? $data['token'] : null;
    }

    $userRole = getUserRole($token);
    $userID = getUserID($token);

    if ($userRole >= $roles['user']) {
        if (isset($data['orderID'])) {
            $orderID = $data['orderID'];
            
            // Перевірка, чи належить замовлення користувачу або чи є користувач редактором
            $stmt = $db->prepare('SELECT UserID FROM Orders WHERE OrderID = :orderID');
            $stmt->bindParam(':orderID', $orderID, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $order = $result->fetchArray(SQLITE3_ASSOC);

            if ($order && ($order['UserID'] == $userID || $userRole >= $roles['editor'])) {
                $stmt = $db->prepare('DELETE FROM Orders WHERE OrderID = :orderID');
                $stmt->bindParam(':orderID', $orderID, SQLITE3_INTEGER);

                if ($stmt->execute()) {
                    echo json_encode(['message' => 'Замовлення успішно видалено.']);
                } else {
                    $error = $db->lastErrorMsg();
                    echo json_encode(['error' => 'Помилка бази даних: ' . $error]);
                }
            } else {
                echo json_encode(['error' => 'Доступ заборонено або замовлення не знайдено.']);
            }
        } else {
            echo json_encode(['error' => 'Невірні дані. Не вказано ID.']);
        }
    } else {
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}