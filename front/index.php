<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main" data-barba="container" data-barba-namespace="login">
      <?php require_once __DIR__ . '/parts/header-shorted.php'; ?>
      <div class="onboarding">
        <div>
          <picture>
            <source type="image/webp" srcset="assets/img/LedUltSoiU-375.webp 375w"><img loading="lazy" decoding="async" src="/assets/img/LedUltSoiU-375.jpeg" class="onboarding__img" alt="" data-aos="fade-up" width="375" height="232">
          </picture>
        </div>
        <div class="container">
          <h1 class="onboarding__title" data-aos="fade-up">Добро пожаловать!</h1>
          <p class="onboarding__text" data-aos="fade-up">Это приложение поможет вам быстро и эффективно управлять отчётностью прямо с вашего рабочего места.</p>
        </div>
      </div>
      <footer>
        <div class="container">
          <p class="footer-buttons">
            <a data-aos="fade-up" href="projects.php" class="button">Проекты</a>
            <a data-aos="fade-up" href="reports.php" class="button button_secondary">Мои отчеты</a>
          </p>
        </div>
      </footer>
    </main>
  </div>
</body>
<?php require_once __DIR__ . '/parts/scripts.php' ?>
</html>
