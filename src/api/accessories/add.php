<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
    
    if (getUserRole($token) >= $roles['editor']) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $db->prepare('
            INSERT INTO Accessories (
                AccessoryManufacturer,
                AccessoryImage,
                AccessoryName,
                AccessoryDescription,
                AccessoryPrice
            ) VALUES (
                :manufacturer,
                :image,
                :name,
                :description,
                :price
            )
        ');

        $stmt->bindParam(':manufacturer', $data['manufacturer']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price'], SQLITE3_FLOAT);
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Аксесуар успішно додано.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Помилка бази даних.']);
        }
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}