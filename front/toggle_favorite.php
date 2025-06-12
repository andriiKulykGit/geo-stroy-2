<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
  exit;
}

if (!isset($_POST['project_id']) || !is_numeric($_POST['project_id'])) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Не указан ID проекта']);
  exit;
}

$project_id = (int)$_POST['project_id'];

$user = current_user();
if (!$user) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
  exit;
}

$stmt = $pdo->prepare("SELECT favorites FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
  http_response_code(404);
  echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
  exit;
}

$favorites = json_decode($result['favorites'], true) ?: [];

$index = array_search($project_id, $favorites);

if ($index !== false) {
  array_splice($favorites, $index, 1);
  $action = 'removed';
} else {
  $favorites[] = $project_id;
  $action = 'added';
}

$stmt = $pdo->prepare("UPDATE users SET favorites = ? WHERE id = ?");
$stmt->execute([json_encode($favorites), $user['id']]);

$_SESSION['user']['favorites'] = json_encode($favorites);

echo json_encode([
  'success' => true,
  'message' => $action === 'added' ? 'Проект добавлен в избранное' : 'Проект удален из избранного',
  'action' => $action,
  'favorites' => $favorites
]);
