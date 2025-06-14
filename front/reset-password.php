<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';

  if (empty($email)) {
    set_flash('error', 'Пожалуйста, введите email');
  } else {
    try {
      $pdo = require __DIR__ . '/../db.php';
      if (!($pdo instanceof PDO)) {
        throw new Exception('Ошибка подключения к базе данных');
      }

      $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
      $stmt->execute([$email]);

      if ($stmt->fetch()) {
        $code = generate_code(4);
        $stmt = $pdo->prepare("INSERT INTO password_reset_codes (email, code) VALUES (?, ?)");
        $stmt->execute([$email, $code]);

        if (send_reset_code($email, $code)) {
          $_SESSION['reset_email'] = $email;
          header("Location: reset-password-2.php");
          exit;
        } else {
          set_flash('error', 'Ошибка отправки письма. Попробуйте позже.');
        }
      } else {
        set_flash('error', 'Пользователь с таким email не найден');
      }
    } catch (Exception $e) {
      set_flash('error', 'Произошла ошибка. Попробуйте позже.');
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
          <div class="input" data-aos="fade-up">
            <input id="email" name="email" placeholder="Email" class="input__control" type="email" required>
            <label for="email" class="input__prepend">
              <span class="icon icon_user icon_current-color icon_medium"></span>
            </label>
          </div>
          <button type="submit" class="button" data-aos="fade-up">Далее</button>
        </form>
      </div>
      <?php require_once __DIR__ . '/parts/footer-login.php' ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>
