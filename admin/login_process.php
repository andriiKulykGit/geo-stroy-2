<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && verify_password($password, $user['password'])) {
    $_SESSION['user'] = $user;
    header("Location: index.php");
    exit;
} else {
    set_flash('error', 'Неверный логин или пароль.');
    header("Location: login.php");
    exit;
}
