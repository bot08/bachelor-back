<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
    
    if (getUserRole($token) >= $roles['editor']) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['id'])) {
            // TODO
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Необхідно вказати ідентифікатор моделі.']);
        }
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}