<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password == $user['password']) {
        $_SESSION['user'] = $user;
        header("Location: projects.php");
        exit;
    } else {
        set_flash('error', 'Неверный email или пароль');
        header("Location: login.php");
        exit;
    }
} catch (PDOException $e) {
    set_flash('error', 'Произошла ошибка при авторизации. Пожалуйста, попробуйте позже.');
    header("Location: login.php");
    exit;
}
