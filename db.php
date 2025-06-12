<?php
$config = require __DIR__ . '/config.php';

try {
    $pdo = new PDO('sqlite:' . $config['db_path']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
