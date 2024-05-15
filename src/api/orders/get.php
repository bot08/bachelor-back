<?php
require '../../core/main.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Перевірка наявності токена в заголовках запиту
    if (!isset($_GET['token'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Токен не надано']);
        return;
    }

    $token = $_GET['token'];
    $userRole = getUserRole($token);

    if ($userRole !== false) {
        $query = "
            SELECT
                o.OrderID,
                o.UserID,
                o.DeliveryAddress,
                o.OrderDate,
                o.TotalAmount,
                u.Username,
                od.DetailID,
                od.FrameID,
                od.LensID,
                od.DioptersLeft,
                od.DioptersRight,
                od.AstigmatismLeft,
                od.AstigmatismRight,
                od.LensSettingDescription,
                od.LensSettingPrice,
                od.ModelID,
                od.AccessoryID,
                od.Quantity,
                od.UnitPrice
            FROM Orders o
            JOIN Users u ON o.UserID = u.UserID
            LEFT JOIN OrderDetails od ON o.OrderID = od.OrderID
        ";

        if ($userRole == $roles['user']) {
            $userID = getUserID($token);
            $query .= " WHERE o.UserID = :userID";
        }

        $stmt = $db->prepare($query);

        if ($userRole == $roles['user']) {
            $stmt->bindValue(':userID', $userID, SQLITE3_INTEGER);
        }

        $result = $stmt->execute();

        $orders = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $orderID = $row['OrderID'];
            if (!isset($orders[$orderID])) {
                $orders[$orderID] = [
                    'OrderID' => $row['OrderID'],
                    'UserID' => $row['UserID'],
                    'DeliveryAddress' => $row['DeliveryAddress'],
                    'OrderDate' => $row['OrderDate'],
                    'TotalAmount' => $row['TotalAmount'],
                    'Username' => $row['Username'],
                    'Details' => []
                ];
            }

            if ($row['DetailID']) {
                $orders[$orderID]['Details'][] = [
                    'DetailID' => $row['DetailID'],
                    'FrameID' => $row['FrameID'],
                    'LensID' => $row['LensID'],
                    'DioptersLeft' => $row['DioptersLeft'],
                    'DioptersRight' => $row['DioptersRight'],
                    'AstigmatismLeft' => $row['AstigmatismLeft'],
                    'AstigmatismRight' => $row['AstigmatismRight'],
                    'LensSettingDescription' => $row['LensSettingDescription'],
                    'LensSettingPrice' => $row['LensSettingPrice'],
                    'ModelID' => $row['ModelID'],
                    'AccessoryID' => $row['AccessoryID'],
                    'Quantity' => $row['Quantity'],
                    'UnitPrice' => $row['UnitPrice']
                ];
            }
        }

        echo json_encode(array_values($orders));
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Доступ заборонено']);
    }
}