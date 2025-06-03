<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

require_login();

$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        ?>
            <a class="project project_favorite" data-aos="fade-up" href="/report.html" draggable="false">
              <div class="project__background project__background_favorite">
                <span class="icon icon_star icon_medium icon_current-color"></span>
                <span>В избранное</span>
              </div>
              <div class="project__inner">
                <div class="project__col">
                  <div class="project__date">
                    <span>2022</span>
                    <span>2024</span>
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
          echo 'Проекты пока не добавлены';
        endif; ?>
      </div>
      <footer class="footer" data-aos="fade-up">
        <div class="container">
          <nav class="nav">
            <ul class="nav__list">
              <li class="nav__item">
                <a href="/projects.html" class="nav__link nav__link_active">
                  <span class="icon icon_checklist icon_big"></span>
                  <span>Проекты</span>
                </a>
              </li>
              <li class="nav__item">
                <a href="/create-report.html" class="nav__link nav__link_add">
                  <span class="icon icon_add icon_big"></span>
                  <span>Создать отчет</span>
                </a>
              </li>
              <li class="nav__item">
                <a href="/reports.html" class="nav__link">
                  <span class="icon icon_list icon_big"></span>
                  <span>Мои отчеты</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </footer>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>