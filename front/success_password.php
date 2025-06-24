<?php
require_once __DIR__ . '/../includes/functions.php';
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main" data-barba="container" data-barba-namespace="login">
      <?php require_once __DIR__ . '/parts/header-shorted.php'; ?>
      <div class="onboarding">
        <div class="onboarding__icon">
          <div class="icon-wrapper" data-aos="fade-up">
            <span class="icon icon_huge icon_success"></span>
          </div>
        </div>
        <div class="container">
          <h1 class="onboarding__title" data-aos="fade-up">Запрос успешно отправлен!</h1>
          <p class="onboarding__text" data-aos="fade-up">В скором времени с вами свяжется администратор.</p>
        </div>
      </div>
      <footer>
        <div class="container">
          <p class="footer-buttons">
            <a data-aos="fade-up" href="login.php" class="button">Войти</a>
          </p>
        </div>
      </footer>
    </main>

  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>
</html>
