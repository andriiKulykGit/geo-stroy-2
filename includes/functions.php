<?php
// 10 years
$lifetime = 60 * 60 * 24 * 365 * 10;

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax'
]);

ini_set('session.gc_maxlifetime', $lifetime);
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

function generate_code($length = 4)
{
    return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function format_date($date_string)
{
    $date = new DateTime($date_string);
    return $date->format('d.m.Y');
}

function get_state_class($state)
{
    $classes = [
        'Разработка тех. проекта' => 'state_gray',
        'Разработка проекта ОВОС' => 'state_blue',
        'Проведение общ. слушаний' => 'state_purple',
        'Мобилизация' => 'state_red',
        'Полевые работы' => 'state_yellow',
        'Завершено' => 'state_green'
    ];

    return $classes[$state] ?? 'state_gray';
}

function the_header($title = '', $isFavoritesVisible = false)
{
    $favorites = '';

    if ($isFavoritesVisible) {
        $favorites = '
        <a href="favorites.php" class="button-favorite">
            <span class="icon icon_large icon_star icon_current-color"></span>
        </a>
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
                <div class="header__col header__col_actions">
                ' . $favorites . '
                  <a href="logout.php">
                    <span class="icon icon_large icon_logout"></span>
                  </a>
                </div>
            </div>
        </div>
    </header>';
}

function the_footer($visibleItems = [true, true, true])
{
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
        if (!$visibleItems[$index]) continue;

        $activeClass = $currentPage === $item['link'] ? 'nav__link_active' : '';
        $addClass = $item['link'] === 'create-report.php' ? ' nav__link_add' : '';

        echo '
        <li class="nav__item">
            <a href="' . $item['link'] . '" class="nav__link ' . $activeClass . $addClass . '">
                <span class="icon ' . $item['icon'] . ' icon_medium"></span>
                <span>' . $item['text'] . '</span>
            </a>
        </li>
        ';
    }

    echo '</ul></nav></div></footer>';
}

function send_reset_code($email, $code) {
    $config = require __DIR__ . '/../config.php';
    $subject = 'Восстановление пароля - ' . $config['site_name'];
    $message = "
    <html>
    <head>
        <title>Восстановление пароля</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .code { font-size: 24px; font-weight: bold; color: #007bff; padding: 10px; background: #f8f9fa; text-align: center; }
            .footer { margin-top: 20px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Восстановление пароля</h2>
            <p>Здравствуйте!</p>
            <p>Вы запросили восстановление пароля. Для продолжения используйте следующий код:</p>
            <div class='code'>{$code}</div>
            <p>Если вы не запрашивали восстановление пароля, проигнорируйте это письмо.</p>
            <div class='footer'>
                <p>Это письмо отправлено автоматически, не отвечайте на него.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . $config['site_name'] . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
        'Reply-To: noreply@' . $_SERVER['HTTP_HOST'],
        'X-Mailer: PHP/' . phpversion()
    );

    return mail($email, $subject, $message, implode("\r\n", $headers));
}

function notify_admins_password_reset($user, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT email FROM users WHERE role = 'admin'");
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($admins)) {
            return false;
        }

        $subject = 'Запрос на сброс пароля';
        $message = "
        <html>
        <head>
            <title>Запрос на сброс пароля</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .user-info { background: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
                .user-info h3 { margin-top: 0; color: #dc3545; }
                .info-row { margin: 8px 0; }
                .info-label { font-weight: bold; color: #495057; }
                .footer { margin-top: 20px; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>🔐 Запрос на сброс пароля</h2>
                <p>Уважаемый администратор!</p>
                <p>Пользователь запросил сброс пароля на сайте.</p>

                <div class='user-info'>
                    <h3>Информация о пользователе:</h3>
                    <div class='info-row'>
                        <span class='info-label'>Имя:</span> " . htmlspecialchars($user['name'] ?? 'Не указано') . "
                    </div>
                    <div class='info-row'>
                        <span class='info-label'>Username:</span> " . htmlspecialchars($user['username'] ?? 'Не указано') . "
                    </div>
                    <div class='info-row'>
                        <span class='info-label'>Email:</span> " . htmlspecialchars($user['email']) . "
                    </div>
                    <div class='info-row'>
                        <span class='info-label'>Дата и время:</span> " . date('d.m.Y H:i:s') . "
                    </div>
                </div>

                <p><strong>Внимание:</strong> Пользователю был отправлен код для восстановления пароля. Если это подозрительная активность, рекомендуется связаться с пользователем для подтверждения.</p>

                <div class='footer'>
                    <p>Это уведомление отправлено автоматически системой безопасности сайта.</p>
                    <p>Дата отправки: " . date('d.m.Y H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $headers = array(
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . 'security@' . $_SERVER['HTTP_HOST'],
            'X-Mailer: PHP/' . phpversion(),
            'X-Priority: 2'
        );

        $success = true;
        foreach ($admins as $admin_email) {
            if (!mail($admin_email, $subject, $message, implode("\r\n", $headers))) {
                $success = false;
            }
        }

        return $success;

    } catch (Exception $e) {
        error_log("Ошибка отправки уведомления администраторам: " . $e->getMessage());
        return false;
    }
}
