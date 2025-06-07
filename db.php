<?php
// db.php — подключение к SQLite базе

$config = require __DIR__ . '/config.php';

try {
    $pdo = new PDO('sqlite:' . $config['db_path']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}


// require_once __DIR__ . '/db.php';
// теперь $pdo — это готовый объект подключения к SQLite
