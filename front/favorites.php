<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$user = current_user();
$favorites = json_decode($user['favorites'] ?? '[]', true);

if (!empty($favorites)) {
  $placeholders = str_repeat('?,', count($favorites) - 1) . '?';
  $stmt = $pdo->prepare("SELECT * FROM projects WHERE id IN ($placeholders) ORDER BY created_at DESC");
  $stmt->execute($favorites);
  $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main main_gray" data-barba="container" data-barba-namespace="login">
      <?php the_header('Избранные проекты') ?>
      <div class="projects">
        <?php
        if ($projects):
          foreach ($projects as $p):
        ?>
            <a class="project project_trash" data-aos="fade-up" data-id="<?= $p['id'] ?>" href="create-report.php?project_id=<?= $p['id'] ?>" draggable="false">
              <div class="project__background project__background_trash">
                <span class="icon icon_trash icon_medium icon_current-color"></span>
                <span>Удалить</span>
              </div>
              <div class="project__inner">
                <div class="project__col">
                  <div class="project__date">
                    <span><?= $p['start_year'] ?? '—' ?></span>
                    <span><?= $p['end_year'] ?? '—' ?></span>
                  </div>
                  <div class="project__name"><?= e($p['name']) ?></div>
                  <div class="project__shapes">
                    <div class="tag tag_<?= strtolower(e($p['seismic'])) ?>"><?= e($p['seismic']) ?></div>
                  </div>
                </div>
                <div class="project__col">
                  <div class="project__link">
                    <span class="icon icon_large icon_chevrown-right icon_current-color"></span>
                  </div>
                </div>
              </div>
            </a>
        <?php
          endforeach;
        else:
          echo '<span class="container">У вас нет избранных проектов</span>';
        endif;
        ?>
      </div>
      <?php the_footer() ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>