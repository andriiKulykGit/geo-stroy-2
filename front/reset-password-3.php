<?php
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['reset_email'])) {
  header("Location: reset-password.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $password = $_POST['newPassword'] ?? '';
  $passwordRepeat = $_POST['newPasswordReapeat'] ?? '';

  if (empty($password) || empty($passwordRepeat)) {
    set_flash('error', 'Пожалуйста, заполните все поля');
  } elseif ($password !== $passwordRepeat) {
    set_flash('error', 'Пароли не совпадают');
  } elseif (strlen($password) < 6) {
    set_flash('error', 'Пароль должен быть не менее 6 символов');
  } else {
    $pdo = require __DIR__ . '/../db.php';

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$password, $_SESSION['reset_email']]);

    $stmt = $pdo->prepare("DELETE FROM password_reset_codes WHERE email = ?");
    $stmt->execute([$_SESSION['reset_email']]);

    unset($_SESSION['reset_email']);

    set_flash('success', 'Пароль успешно изменен');
    header("Location: login.php");
    exit;
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
            <input id="newPassword" name="newPassword" placeholder="Новый пароль" class="input__control" type="text" required>
            <label for="newPassword" class="input__prepend">
              <span class="icon icon_lock icon_current-color icon_medium"></span>
            </label>
          </div>
          <div class="input" data-aos="fade-up">
            <input id="newPasswordReapeat" name="newPasswordReapeat" placeholder="Повторите новый пароль" class="input__control" type="text" required>
            <label for="newPasswordReapeat" class="input__prepend">
              <span class="icon icon_lock icon_current-color icon_medium"></span>
            </label>
          </div>
          <button type="submit" class="button" data-aos="fade-up">Восстановить</button>
        </form>
      </div>
      <?php require_once __DIR__ . '/parts/footer-login.php' ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>
