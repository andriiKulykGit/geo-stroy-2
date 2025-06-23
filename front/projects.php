<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

require_login();

$user = current_user();
$favorites = json_decode($user['favorites'] ?? '[]', true);

if ($user['role'] === 'admin' || $user['role'] === 'viewer') {
  $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
  $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $stmt = $pdo->prepare("SELECT p.* FROM projects p
                        JOIN project_users pu ON p.id = pu.project_id
                        WHERE pu.user_id = ?
                        ORDER BY p.created_at DESC");
  $stmt->execute([$user['id']]);
  $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body>
  <div class="wrapper" data-barba="wrapper">
    <main class="main main_gray" data-barba="container" data-barba-namespace="login">
      <?php the_header('Список проектов', true) ?>
      <div class="projects">
        <?php
        if ($projects):
          foreach ($projects as $p):
            $isFavorites = in_array($p['id'], $favorites) ? ' in-favorites' : '';
        ?>
            <a class="project project_favorite <?php
            if($isFavorites) {
              echo 'project_is-favorite';
            }
            ?>" data-aos="fade-up" href="reports_by_project.php?project_id=<?= $p['id'] ?>" data-id="<?= $p['id'] ?>" draggable="false">
              <div class="project__background project__background_favorite">
                <span class="icon icon_star icon_medium icon_current-color"></span>
                <span>В избранное</span>
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
          echo '<div class="container">Проекты пока не добавлены или вы не назначены ни на один проект</div>';
        endif; ?>
      </div>
      <?php the_footer() ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>
