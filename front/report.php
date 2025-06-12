<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: reports.php");
  exit;
}

$report_id = (int)$_GET['id'];
$user = current_user();
$user_id = $user['id'];

$stmt = $pdo->prepare("SELECT r.*, p.name as project_name FROM reports r
                      LEFT JOIN projects p ON r.project_id = p.id
                      WHERE r.id = ? AND r.user_id = ?");
$stmt->execute([$report_id, $user_id]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
  header("Location: reports.php");
  exit;
}

$files = json_decode($report['files'], true) ?: [];
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main main_gray" data-barba="container" data-barba-namespace="login">
      <?php the_header('Просмотр отчета №' . $report['id']) ?>
      <div class="create">
        <div class="container">
          <div class="create__body">
            <div class="input" data-aos="fade-up">
              <input id="nameOfProject" name="nameOfProject" value="<?= e($report['project_name']) ?>" class="input__control " type="text" readonly="">
              <label for="nameOfProject" class="input__label">
                Проект
              </label>
              <label for="nameOfProject" class="input__prepend">
                <span class="icon icon_user icon_current-color icon_medium"></span>
              </label>
            </div>
            <div class="input" data-aos="fade-up">
              <div class="input__control">
                <div class="state <?= get_state_class($report['state']) ?>">
                  <?= e($report['state']) ?>
                </div>
              </div>
              <div class="input__prepend">
                <span class="icon icon_checklist icon_current-color icon_medium"></span>
              </div>
            </div>
            <div class="input" data-aos="fade-up">
              <textarea id="message" name="message" class="input__control " readonly=""><?= e($report['comment']) ?></textarea>
              <label for="message" class="input__label">
                Комментарий
              </label>
              <label for="message" class="input__prepend">
                <span class="icon icon_comment icon_current-color icon_medium"></span>
              </label>
            </div>
            <?php if (!empty($files)): ?>
              <?php foreach ($files as $file): ?>
                <div class="file file_default" data-aos="fade-up">
                  <div class="file__icon">
                    <span class="icon icon_file icon_huge"></span>
                  </div>
                  <div class="file__inner">
                    <div class="file__name">
                      <span><?= e($file) ?></span>
                    </div>
                    <ul class="file__subtext">
                      <li class="file__item">
                        <?php
                        $file_path = __DIR__ . '/../uploads/' . $file;
                        $file_size = file_exists($file_path) ? filesize($file_path) : 0;
                        echo round($file_size / 1024, 2) . ' KB';
                        ?>
                      </li>
                    </ul>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="no-files" data-aos="fade-up">
                <p>Нет прикрепленных файлов</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php the_footer([1,0,1]) ?>
    </main>
  </div>
  <script src="assets/js/main.js"></script>
</body>

</html>
