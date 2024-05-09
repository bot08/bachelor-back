<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
    
    if (getUserRole($token) >= $roles['editor']) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['id'])) {
            $stmt = $db->prepare('DELETE FROM Accessories WHERE AccessoryID = :id');
            $stmt->bindValue(':id', $data['id'], SQLITE3_INTEGER);
            
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Аксесуар успішно видалено.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Помилка бази даних.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Не вказано ідентифікатор аксесуару.']);
        }
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}