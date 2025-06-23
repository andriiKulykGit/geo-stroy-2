<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $password == $user['password']) {
    $_SESSION['user'] = $user;
    header("Location: index.php");
    exit;
} else {
    set_flash('error', 'Неверный логин или пароль.');
    header("Location: login.php");
    exit;
}
