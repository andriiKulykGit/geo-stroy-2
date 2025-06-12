<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

require_login();

$user = current_user();
$user_id = $user['id'];

$stmt = $pdo->prepare("SELECT r.*, p.name as project_name FROM reports r
                      LEFT JOIN projects p ON r.project_id = p.id
                      WHERE r.user_id = ?
                      ORDER BY r.created_at DESC");
$stmt->execute([$user_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main main_gray" data-barba="container" data-barba-namespace="login">
      <?php the_header('Мои отчеты') ?>
      <div class="projects">
        <?php if (count($reports) > 0): ?>
          <?php foreach ($reports as $report): ?>
            <a class="report" data-aos="fade-up" href="report.php?id=<?= $report['id'] ?>" draggable="false">
              <div class="report__inner">
                <div class="report__col">
                  <div class="report__body">
                    <div class="report__state">
                      <div class="state <?= get_state_class($report['state']); ?>">
                        <?= $report['state'] ?>
                      </div>
                    </div>
                    <p class="report__name">
                      <span><?= 'Отчет №' . $report['id'] ?></span>
                      <span class="report__date">От <?= format_date($report['created_at']); ?></span>
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
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-reports" data-aos="fade-up">
            <p>У вас пока нет отчетов</p>
          </div>
        <?php endif; ?>
      </div>
      <?php the_footer() ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>
