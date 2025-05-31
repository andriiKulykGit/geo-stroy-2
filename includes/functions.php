<?php

session_start();

function set_flash($key, $message)
{
    $_SESSION['_flash'][$key] = $message;
}

function get_flash($key)
{
    if (isset($_SESSION['_flash'][$key])) {
        $msg = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $msg;
    }
    return null;
}

function is_logged_in()
{
    return isset($_SESSION['user']);
}

function require_login()
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hash)
{
    return password_verify($password, $hash);
}

function generate_code($length = 4)
{
    return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
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
