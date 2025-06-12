<?php
$dbFile = __DIR__ . '/database.sqlite';

if (file_exists($dbFile)) {
    exit("База данных уже существует.
");
}

function loadEnv($path)
{
    if (!file_exists($path)) {
        exit("Файл .env не найден. Создайте его.");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

loadEnv(__DIR__ . '/.env');

if (!getenv('ADMIN_NAME') || !getenv('ADMIN_EMAIL') || !getenv('ADMIN_PASSWORD')) {
    exit("В файле .env отсутствуют необходимые переменные (ADMIN_NAME, ADMIN_EMAIL, ADMIN_PASSWORD).
");
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
    start_year INTEGER,
    end_year INTEGER,
    created_by INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    reports_ids TEXT
);

CREATE TABLE project_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(project_id, user_id)
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

$adminName = getenv('ADMIN_NAME');
$adminEmail = getenv('ADMIN_EMAIL');
$adminPassword = password_hash(getenv('ADMIN_PASSWORD'), PASSWORD_DEFAULT);
$adminRole = 'admin';

$stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$adminName, $adminEmail, $adminPassword, $adminRole]);

echo "База данных и таблицы успешно созданы.\n";
