<?php
// init_db.php — запускать один раз для инициализации БД
$dbFile = __DIR__ . '/database.sqlite';

if (file_exists($dbFile)) {
    exit("База данных уже существует.\n");
}

$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// SQL для создания таблиц
$sql = "
CREATE TABLE site (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    domain TEXT,
    theme TEXT,
    allow_reg INTEGER DEFAULT 1,
    maintenance_mode INTEGER DEFAULT 0
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('admin', 'user')) NOT NULL,
    favorites TEXT DEFAULT '[]',
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    seismic TEXT CHECK(seismic IN (
        '2D',
        '3D'
    )) NOT NULL,
    state TEXT CHECK(state IN (
        'Разработка тех. проекта',
        'Разработка проекта ОВОС',
        'Проведение общ. слушаний',
        'Мобилизация',
        'Полевые работы',
        'Завершено'
    )) NOT NULL,
    created_by INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    reports_ids TEXT
);

CREATE TABLE reports (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER,
    user_id INTEGER,
    state TEXT CHECK(state IN (
        'Разработка тех. проекта',
        'Разработка проекта ОВОС',
        'Проведение общ. слушаний',
        'Мобилизация',
        'Полевые работы',
        'Завершено'
    )) NOT NULL,
    comment TEXT,
    files TEXT DEFAULT '[]',
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE password_reset_codes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL,
    code TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
";

$db->exec($sql);

echo "База данных и таблицы успешно созданы.\n";
