<?php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['reset_email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email не найден']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if ($email !== $_SESSION['reset_email']) {
    http_response_code(400);
    echo json_encode(['error' => 'Неверный email']);
    exit;
}

$pdo = require __DIR__ . '/../db.php';
$code = generate_code(4);

$stmt = $pdo->prepare("INSERT INTO password_reset_codes (email, code) VALUES (?, ?)");
$stmt->execute([$email, $code]);

if (send_reset_code($email, $code)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка отправки письма']);
}
