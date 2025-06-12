<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: reset-password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';

    if (empty($code)) {
        set_flash('error', 'Пожалуйста, введите код');
    } else {
        $pdo = require __DIR__ . '/../db.php';
        $stmt = $pdo->prepare("SELECT * FROM password_reset_codes WHERE email = ? AND code = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$_SESSION['reset_email'], $code]);

        if ($stmt->fetch()) {
            header("Location: reset-password-3.php");
            exit;
        } else {
            set_flash('error', 'Неверный код');
        }
    }
}
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
    <div class="wrapper" data-barba="wrapper">
        <main class="main" data-barba="container" data-barba-namespace="login">
            <?php require_once __DIR__ . '/parts/header-shorted.php'; ?>
            <div class="container">
                <form method="post" action="" class="form">
                    <div class="form__title" data-aos="fade-up">Восстановление пароля</div>
                    <?php if ($error = get_flash('error')): ?>
                        <div class="form__error" data-aos="fade-up" style="color:red"><?= e($error) ?></div>
                    <?php endif; ?>
                    <div class="form__bubbles bubble-container">
                        <div class="input" data-aos="fade-up">
                            <input name="code[]" placeholder=" " class="input__control input__control_bubble" type="text" required inputmode="numeric" maxlength="1" pattern="[0-9]">
                        </div>
                        <div class="input" data-aos="fade-up">
                            <input name="code[]" placeholder=" " class="input__control input__control_bubble" type="text" required inputmode="numeric" maxlength="1" pattern="[0-9]">
                        </div>
                        <div class="input" data-aos="fade-up">
                            <input name="code[]" placeholder=" " class="input__control input__control_bubble" type="text" required inputmode="numeric" maxlength="1" pattern="[0-9]">
                        </div>
                        <div class="input" data-aos="fade-up">
                            <input name="code[]" placeholder=" " class="input__control input__control_bubble" type="text" required inputmode="numeric" maxlength="1" pattern="[0-9]">
                        </div>
                    </div>
                    <div class="form__label">
                        <span data-aos="fade-up">Не пришел код?</span>
                        <br>
                        <button type="button" data-aos="fade-up" class="timer-wrapper">Отправить код</button>
                    </div>
                    <button type="submit" class="button" data-aos="fade-up">Далее</button>
                </form>
            </div>
            <?php require_once __DIR__ . '/parts/footer-login.php' ?>
        </main>
    </div>
    <?php require_once __DIR__ . '/parts/scripts.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const timerWrapper = document.querySelector('.timer-wrapper');
            let interval = null;

            const offTimer = () => {
                clearInterval(interval);
                timerWrapper.innerHTML = 'Отправить код';
                timerWrapper.removeAttribute('disabled');
            };

            const onTimer = () => {
                let time = 30;
                timerWrapper.setAttribute('disabled', true);
                timerWrapper.innerHTML = 'Отправить повторно через 0:<span class="timer">30</span>';

                interval = setInterval(() => {
                    time--;
                    const timer = timerWrapper.querySelector('.timer');
                    timer.textContent = time < 10 ? `0${time}` : `${time}`;
                    if (time <= 0) offTimer();
                }, 1000);
            };

            timerWrapper.addEventListener('click', () => {
                fetch('send_code.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: '<?= $_SESSION['reset_email'] ?>'
                    })
                }).then(() => onTimer());
            });
        });
    </script>
</body>

</html>
