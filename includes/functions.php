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

function the_header($title = '', $isFavoritesVisible = false)
{
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
}

function the_footer($visibleItems = [true, true, true]) {
    $currentPage = basename($_SERVER['PHP_SELF']);

    $items = [
        [
            'icon' => 'icon_checklist',
            'text' => 'Проекты',
            'link' => 'projects.php',
        ],
        [
            'icon' => 'icon_add',
            'text' => 'Создать отчет',
            'link' => 'create-report.php',
        ],
        [
            'icon' => 'icon_list',
            'text' => 'Мои отчеты',
            'link' => 'reports.php',
        ],
    ];

    echo '<footer class="footer" data-aos="fade-up">
            <div class="container">
            <nav class="nav">
                <ul class="nav__list">';

    foreach ($items as $index => $item) {
        if ($visibleItems[$index]) {
            $activeClass = $currentPage === $item['link'] ? 'nav__link_active' : '';
        }

        $addClass = $item['link'] === 'create-report.php'? ' nav__link_add' : '';

        echo '
        <li class="nav__item">
            <a href="' . $item['link'] . '" class="nav__link ' . $activeClass . $addClass. '">
                <span class="icon ' . $item['icon'] . ' icon_big"></span>
                <span>' . $item['text'] . '</span>
            </a>
        </li>
        ';
    }

    echo '</ul></nav></div></footer>';
}
