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
        <div class="onboarding__icon">
          <div class="icon-wrapper" data-aos="fade-up">
            <span class="icon icon_huge icon_success"></span>
          </div>
        </div>
        <div class="container">
          <h1 class="onboarding__title" data-aos="fade-up">Отчет успешно отправлен!</h1>
          <p class="onboarding__text" data-aos="fade-up">Вы можете посмотреть сформированный отчет в разделе «Мои отчеты»</p>
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
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>
</html>