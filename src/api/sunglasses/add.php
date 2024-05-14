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

        $stmt = $db->prepare('
            INSERT INTO SunglassesModels (
                ModelManufacturer, 
                ModelName, 
                ModelImage, 
                ModelPolarization, 
                ModelDescription, 
                ModelPrice
            ) VALUES (
                :manufacturer, 
                :name, 
                :image, 
                :polarization, 
                :description, 
                :price
            )
        ');

        $stmt->bindParam(':manufacturer', $data['manufacturer']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':polarization', $data['polarization'], SQLITE3_INTEGER); // BOOLEAN
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price'], SQLITE3_FLOAT); // DECIMAL(10, 2)
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Сонцезахисні окуляри успішно додано.']);
        } else {
            //http_response_code(500);
            echo json_encode(['error' => 'Помилка бази даних.']);
        }
    } else {
        //http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}