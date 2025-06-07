<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
  header("Location: index.php");
  exit;
}
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main" data-barba="container" data-barba-namespace="login">
      <?php require_once __DIR__ . '/parts/header-shorted.php'; ?>
      <div class="container">
        <form method="post" action="login_process.php" class="form" id="loginForm">
          <div class="form__title" data-aos="fade-up">Вход в систему</div>
          <?php if ($error = get_flash('error')): ?>
            <div class="form__error" data-aos="fade-up" style="color:red"><?= e($error) ?></div>
          <?php endif; ?>
          <div class="input" data-aos="fade-up">
            <input id="email" name="email" placeholder="Email" class="input__control" type="email" required>
            <label for="email" class="input__prepend">
              <span class="icon icon_user icon_current-color icon_medium"></span>
            </label>
          </div>
          <div class="input" data-aos="fade-up">
            <input id="password" name="password" placeholder="Пароль" class="input__control" type="password" required>
            <label for="password" class="input__prepend">
              <span class="icon icon_lock icon_current-color icon_medium"></span>
            </label>
          </div>
          <a data-aos="fade-up" href="reset-password.php" class="form__forgot">Забыли пароль?</a>
          <button type="submit" data-aos="fade-up" class="button">Войти</button>
        </form>
      </div>
      <?php require_once __DIR__ . '/parts/footer-login.php' ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>
