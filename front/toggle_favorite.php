<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

// Проверяем авторизацию
require_login();

// Проверяем, что запрос выполнен методом POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

// Проверяем наличие project_id в запросе
if (!isset($_POST['project_id']) || !is_numeric($_POST['project_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Не указан ID проекта']);
    exit;
}

$project_id = (int)$_POST['project_id'];

// Получаем текущего пользователя
$user = current_user();
if (!$user) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

// Получаем текущий список избранного пользователя
$stmt = $pdo->prepare("SELECT favorites FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    http_response_code(404); // Not Found
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit;
}

// Декодируем JSON-массив избранных проектов
$favorites = json_decode($result['favorites'], true) ?: [];

// Проверяем, есть ли уже этот проект в избранном
$index = array_search($project_id, $favorites);

// Если проект уже в избранном - удаляем его, иначе - добавляем
if ($index !== false) {
    // Удаляем проект из избранного
    array_splice($favorites, $index, 1);
    $action = 'removed';
} else {
    // Добавляем проект в избранное
    $favorites[] = $project_id;
    $action = 'added';
}

// Обновляем список избранного в базе данных
$stmt = $pdo->prepare("UPDATE users SET favorites = ? WHERE id = ?");
$stmt->execute([json_encode($favorites), $user['id']]);

// Обновляем данные в сессии
$_SESSION['user']['favorites'] = json_encode($favorites);

// Возвращаем успешный ответ
echo json_encode([
    'success' => true, 
    'message' => $action === 'added' ? 'Проект добавлен в избранное' : 'Проект удален из избранного',
    'action' => $action,
    'favorites' => $favorites
]);