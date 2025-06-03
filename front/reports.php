<?php
require_once __DIR__ . '/../includes/functions.php';

require_login();
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main main_gray" data-barba="container" data-barba-namespace="login">
      <?php the_header('Мои отчеты') ?>
      <div class="projects">
        <a class="report" data-aos="fade-up" href="/report.html" draggable="false">
          <div class="report__inner">
            <div class="report__col">
              <div class="report__body">
                <div class="report__state">
                  <div class="state state_green">
                    Завершено
                  </div>
                </div>
                <p class="report__name">
                  <span>Отчет №4123</span>
                  <span class="report__date">04.04.2025</span>
                </p>
              </div>
            </div>
            <div class="report__col">
              <div class="report__link">
                <span class="icon icon_large icon_chevrown-right icon_current-color"></span>
              </div>
            </div>
          </div>
        </a>
      </div>
      <?php the_footer() ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>
</html>