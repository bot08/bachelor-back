<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;
    $data = json_decode(file_get_contents('php://input'), true);
    
    if(!isset($token)){
        $token = isset($data['token']) ? $data['token'] : null;
    }
    
    $orderData = $data['order'];

    $userID = getUserID($token);
    
    if (isset($userID)) {
        if (isset($data['order'])) {
            $orderData = $data['order'];
            $db->exec('BEGIN TRANSACTION');
            
            try {
                // Вставка замовлення
                $stmt = $db->prepare('
                    INSERT INTO Orders (UserID, DeliveryAddress, OrderDate, TotalAmount, FastDelivery)
                    VALUES (:userID, :deliveryAddress, :orderDate, :totalAmount, :fastDelivery)
                ');
                $stmt->bindValue(':userID', $userID, SQLITE3_INTEGER);
                $stmt->bindValue(':deliveryAddress', $orderData['deliveryAddress'], SQLITE3_TEXT);
                $stmt->bindValue(':orderDate', date('Y-m-d'), SQLITE3_TEXT);
                $stmt->bindValue(':totalAmount', $orderData['totalAmount'], SQLITE3_FLOAT);
                $stmt->bindValue(':fastDelivery', $orderData['fastDelivery'] ?? 0, SQLITE3_INTEGER);
                $stmt->execute();

                $orderID = $db->querySingle('SELECT last_insert_rowid() AS OrderID');

                // Вставка деталей замовлення
                $stmt = $db->prepare('
                    INSERT INTO OrderDetails (
                        OrderID, FrameID, LensID, DioptersLeft, DioptersRight,
                        AstigmatismLeft, AstigmatismRight, LensSettingDescription,
                        LensSettingPrice, ModelID, AccessoryID, Quantity, UnitPrice, DP
                    )
                    VALUES (
                        :orderID, :frameID, :lensID, :dioptersLeft, :dioptersRight,
                        :astigmatismLeft, :astigmatismRight, :lensDescription,
                        :lensPrice, :modelID, :accessoryID, :quantity, :unitPrice, :dp
                    )
                ');
                $stmt->bindValue(':orderID', $orderID, SQLITE3_INTEGER);
                $stmt->bindValue(':frameID', isset($orderData['frameID']) ? $orderData['frameID'] : null, SQLITE3_INTEGER);
                $stmt->bindValue(':lensID', isset($orderData['lensID']) ? $orderData['lensID'] : null, SQLITE3_INTEGER);
                $stmt->bindValue(':dioptersLeft', isset($orderData['dioptersLeft']) ? $orderData['dioptersLeft'] : null, SQLITE3_FLOAT);
                $stmt->bindValue(':dioptersRight', isset($orderData['dioptersRight']) ? $orderData['dioptersRight'] : null, SQLITE3_FLOAT);
                $stmt->bindValue(':astigmatismLeft', isset($orderData['astigmatismLeft']) ? $orderData['astigmatismLeft'] : null, SQLITE3_FLOAT);
                $stmt->bindValue(':astigmatismRight', isset($orderData['astigmatismRight']) ? $orderData['astigmatismRight'] : null, SQLITE3_FLOAT);
                $stmt->bindValue(':lensDescription', isset($orderData['lensDescription']) ? $orderData['lensDescription'] : null, SQLITE3_TEXT);
                $stmt->bindValue(':lensPrice', isset($orderData['lensPrice']) ? $orderData['lensPrice'] : null, SQLITE3_FLOAT);
                $stmt->bindValue(':modelID', isset($orderData['modelID']) ? $orderData['modelID'] : null, SQLITE3_INTEGER);
                $stmt->bindValue(':accessoryID', isset($orderData['accessoryID']) ? $orderData['accessoryID'] : null, SQLITE3_INTEGER);
                $stmt->bindValue(':quantity', isset($orderData['quantity']) ? $orderData['quantity'] : 1, SQLITE3_INTEGER);
                $stmt->bindValue(':unitPrice', isset($orderData['unitPrice']) ? $orderData['unitPrice'] : 0, SQLITE3_FLOAT);
                $stmt->bindValue(':dp', $orderData['dp'] ?? null, SQLITE3_FLOAT);
                $stmt->execute();

                $db->exec('COMMIT TRANSACTION');
                echo json_encode(['message' => 'Замовлення успішно створено.']);
            } catch (Exception $e) {
                $db->exec('ROLLBACK TRANSACTION');
                //http_response_code(500);
                echo json_encode(['error' => 'Помилка бази даних: ' . $e->getMessage()]);
            }
        } else {
            //http_response_code(400);
            echo json_encode(['error' => 'Дані замовлення не надано.']);
        }
    } else {
        //http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
} else {
    //http_response_code(405);
    echo json_encode(['error' => 'Неприпустимий метод запиту.']);
}