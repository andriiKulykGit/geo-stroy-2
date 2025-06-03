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

function the_header($title = '', $isFavoritesVisible = false) {
    $favorites = '';

    if ($isFavoritesVisible) {
        $favorites = '
        <div class="header__col">
            <a href="favorites.php" class="button-favorite">
                <span class="icon icon_large icon_star icon_current-color"></span>
            </a>
        </div>
        ';
    }

    echo '
    <header class="header header_main" data-aos="fade-down">
        <div class="container">
            <div class="header__inner">
                <div class="header__col">
                    <button type="button" class="to-back" onclick="window.history.back()">
                    <span class="icon icon_small icon_chevrown-left icon_current-color"></span>
                    </button>
                    <span>' . $title . '</span>
                </div>
                ' . $favorites . '
            </div>
        </div>
    </header>';

    return;
}