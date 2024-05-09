<?php
require '../core/main.php';
$upload_dir = '../database/images/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Перевірка, чи було завантажено файл
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $file_name = basename($file['name']);
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        // Перевірка розміру файлу (максимальний розмір 2 МБ)
        if ($file_size > 2097152) {
            http_response_code(400);
            echo json_encode(['error' => 'Розмір файлу перевищує допустимий ліміт 2 МБ.']);
            exit;
        }

        // Створення унікального імені файлу
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_name = uniqid() . '.png';
        $upload_path = $upload_dir . $file_name;

        // Створення об'єкту зображення
        $image = null;
        switch (strtolower($file_ext)) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file_tmp);
                break;
            case 'png':
                $image = imagecreatefrompng($file_tmp);
                break;
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Некоректний формат файлу.']);
                exit;
        }

        // Збереження зображення у форматі PNG
        if ($image !== null) {
            if (imagepng($image, $upload_path)) {
                echo json_encode(['path' => $upload_path]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Помилка під час збереження файлу.']);
            }
            imagedestroy($image);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Файл не був завантажений.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не дозволений.']);
}