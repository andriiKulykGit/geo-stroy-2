<?php
$uploadDir = '../uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $target = $uploadDir . basename($file['name']);

    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo 'Ошибка при загрузке файла.';
        exit;
    }

    if ($file['size'] > 50 * 1024 * 1024) {
        http_response_code(413);
        echo 'Файл слишком большой.';
        exit;
    }

    if (move_uploaded_file($file['tmp_name'], $target)) {
        http_response_code(200);
        // Возвращаем имя файла для добавления в список загруженных файлов
        echo json_encode(['success' => true, 'filename' => basename($file['name'])]);
    } else {
        http_response_code(500);
        echo 'Ошибка при сохранении файла.';
    }
} else {
    http_response_code(400);
    echo 'Неверный запрос.';
}
