<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if(!isset($token)){
        $token = isset($data['token']) ? $data['token'] : null;
    }
    
    if (getUserRole($token) >= $roles['editor']) {
        
        if (data['id']) {
            $stmt = $db->prepare('DELETE FROM SunglassesModels WHERE ModelID = :id');
            $stmt->bindValue(':id', $data['id'], SQLITE3_INTEGER);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Сонцезахисні окуляри успішно видалено.']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Помилка бази даних.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Не вказано ідентифікатор моделі.']);
        }
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}