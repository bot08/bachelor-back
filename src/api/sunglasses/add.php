<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
    
    if (getUserRole($token) >= $roles['editor']) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // КОД НИЖЧЕ ЦЕ КОСТИЛЬ БО ПО НОРМАЛЬНОМУ НЕ ХОЧЕ (ЗАКОМЕНТОВАНИЙ)
        $sql = "
        INSERT INTO SunglassesModels (ModelManufacturer, ModelName, ModelImage, ModelPolarization, ModelDescription, ModelPrice)
        VALUES 
        ('".$data['manufacturer']."', '".$data['name']."', '".$data['image']."', ".$data['polarization'].", '".$data['description']."', ".$data['price'].")
        ";
        $db->exec($sql);

        /*
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
        
        $stmt->bindValue(':manufacturer', $data['manufacturer'], SQLITE3_TEXT);
        $stmt->bindValue(':name', $data['name'], SQLITE3_TEXT);
        $stmt->bindValue(':image', $data['image'], SQLITE3_TEXT);
        $stmt->bindValue(':polarization', $data['polarization'] ?? false, SQLITE3_INTEGER);
        $stmt->bindValue(':description', $data['description'], SQLITE3_TEXT);
        $stmt->bindValue(':price', $data['price'], SQLITE3_NUMERIC);
        
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Сонцезахисні окуляри успішно додано.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Помилка бази даних.']);
        }
        */
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}