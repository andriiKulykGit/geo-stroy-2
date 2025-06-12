<?php
// Отримуємо дані з POST запиту
$data = json_decode(file_get_contents('php://input'), true);

// Перевіряємо, чи є ім'я файлу
if (!isset($data['filename']) || empty($data['filename'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ім\'я файлу не вказано']);
    exit;
}

$filename = $data['filename'];
$uploadDir = '../uploads/';
$filePath = $uploadDir . basename($filename);

// Перевіряємо, чи існує файл
if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Файл не знайдено']);
    exit;
}

// Спроба видалити файл
if (unlink($filePath)) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Файл успішно видалено']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Помилка при видаленні файлу']);
}